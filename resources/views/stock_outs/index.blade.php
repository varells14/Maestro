@extends('layouts.app')

@section('content')
<div class="container-fluid my-38">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h3 class="m-0 font-weight-bold">Stock Out Data</h3>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter by Date
                </button>
                <button class="btn btn-light ms-2" data-bs-toggle="modal" data-bs-target="#stockOutModal" 
                    data-action="{{ route('stock-outs.store') }}"
                    data-mode="create">
                    <i class="fas fa-plus"></i> Add Outgoing Stock
                </button>
                <div class="dropdown ms-2">
                    <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-light fa-file-arrow-down"></i> Report
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('stock-outs.export', [
                                'start_date' => request('start_date'),
                                'end_date' => request('end_date')
                            ]) }}">
                                <i class="fas fa-file-excel text-success"></i> Export to Excel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('categories.index', ['format' => 'pdf']) }}">
                                <i class="fas fa-file-pdf text-danger"></i> Export to PDF
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                @if(request('start_date') && request('end_date'))
                    <div class="alert alert-info">
                        Displaying data from <strong>{{ date('d/m/Y', strtotime(request('start_date'))) }}</strong>
                        to <strong>{{ date('d/m/Y', strtotime(request('end_date'))) }}</strong>.
                        <a href="{{ route('stock-outs.index') }}" class="btn btn-sm btn-warning ms-2">Reset Filter</a>
                    </div>
                @endif

                <table class="table table-hover table-striped align-middle" id="stockOutTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Date</th>
                            <th width="20%">Product Name</th>
                            <th width="8%">Quantity</th>
                            <th width="20%">Notes</th>
                            <th width="10%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockOuts as $index => $stockOut)
                            <tr>
                                <td class="fw-bold">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ date('d/m/Y', strtotime($stockOut->date)) }}</td>
                                <td class="fw-bold">{{ $stockOut->product->name }}</td>
                                <td class="fw-bold">{{ $stockOut->quantity }}</td>
                                <td class="fw-bold">{{ Str::limit($stockOut->notes, 50) }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="actionBtn{{ $stockOut->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog"></i> Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="actionBtn{{ $stockOut->id }}">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editStockOutModal"
                                                    data-id="{{ $stockOut->id }}"
                                                    data-reference_number="{{ $stockOut->reference_number }}"
                                                    data-date="{{ $stockOut->date }}"
                                                    data-product_id="{{ $stockOut->product_id }}"
                                                    data-supplier_id="{{ $stockOut->supplier_id }}"
                                                    data-quantity="{{ $stockOut->quantity }}"
                                                    data-notes="{{ $stockOut->notes }}">
                                                    <i class="fas fa-edit text-primary"></i> Edit
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteStockOutModal"
                                                    data-id="{{ $stockOut->id }}">
                                                    <i class="fas fa-trash text-danger"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data untuk tanggal yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if(count($stockOuts) == 0)
                <div class="text-center p-5">
                    <i class="fas fa-dolly-flatbed fa-3x text-muted"></i>
                    <p class="mt-3">No stock out data available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="stockOutForm" method="POST">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="stockOutModalLabel"><i class="fas fa-plus-circle"></i> Add Stock Out Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_method" id="form_method" value="POST">
                <div class="row g-3">
                    <!-- <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="reference_number" id="reference_number" class="form-control" placeholder="Reference No." required>
                            <label for="reference_number">Reference No.</label>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="date" name="date" id="date" class="form-control" placeholder="Date" required>
                            <label for="date">Date</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                                        {{ $product->code }} - {{ $product->name }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="product_id">Product</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Initial Stock" value="0" min="0" required onkeypress="return event.charCode >= 48 && event.charCode <= 57" step="1">
                            <label for="quantity">Quantity</label>
                            <div id="stock_warning" class="invalid-feedback">Quantity exceeds available stock</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="destination" id="destination" class="form-control text-uppercase" placeholder="Destination" required oninput="this.value = this.value.toUpperCase()">
                            <label for="destination">Destination</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <textarea name="notes" id="notes" class="form-control text-uppercase" style="height: 100px" placeholder="Notes" oninput="this.value = this.value.toUpperCase()"></textarea>
                            <label for="notes">Notes</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="submit_btn">
                     Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal for each stock out item -->
@foreach($stockOuts as $stockOut)
<div class="modal fade" id="deleteStockOutModal{{ $stockOut->id }}" tabindex="-1" aria-labelledby="deleteLabel{{ $stockOut->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('stock-outs.destroy', $stockOut->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteLabel{{ $stockOut->id }}">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <p class="mb-0">Are you sure you want to delete the following stock out data:</p>
                <h4 class="text-danger mt-2">{{ $stockOut->product->name }} ({{ $stockOut->quantity }} units)</h4>
                <p class="text-muted small">Stock will be returned to inventory!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('stock-outs.index') }}" method="GET" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterModalLabel">Filter Stock Out by Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="mb-3">
          <label for="end_date" class="form-label">End Date</label>
          <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('stock-outs.index') }}" class="btn btn-secondary">Reset</a>
        <button type="submit" class="btn btn-primary">Apply Filter</button>
      </div>
    </form>
  </div>
</div>

@endforeach

@push('scripts')
<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    // Initialize Select2
    $('#product_id').select2({
        dropdownParent: $('#stockOutModal'),
        theme: 'bootstrap-5'
    });

    $('#product_id').on('change', function () {
        const maxStock = $(this).find('option:selected').data('stock') || 0;
        $('#quantity').on('input', function () {
            const qty = parseInt($(this).val());
            if (qty > maxStock) {
                $(this).addClass('is-invalid');
                $('#stock_warning').show();
                $('#submit_btn').prop('disabled', true);
            } else {
                $(this).removeClass('is-invalid');
                $('#stock_warning').hide();
                $('#submit_btn').prop('disabled', false);
            }
        });
    });
});
</script>
@endpush
@endsection
