@extends('layouts.app')

@section('content')
<div class="container-fluid my-38">
    <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
    <h3 class="m-0 font-weight-bold">Incoming Stock Data</h3>
    <div class="d-flex align-items-center">
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="fas fa-filter"></i> Filter by Date
        </button>
        <button class="btn btn-light ms-2" data-bs-toggle="modal" data-bs-target="#addStockInModal">
            <i class="fas fa-plus"></i> Add Incoming Stock
        </button>
        <div class="dropdown ms-2">
            <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-light fa-file-arrow-down"></i> Report
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <li>
                <a class="dropdown-item"
   href="{{ route('stock-ins.export', [
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
    <a href="{{ route('stock-ins.index') }}" class="btn btn-sm btn-warning ms-2">Reset Filter</a>
</div>
@endif

<table class="table table-hover table-striped align-middle" id="stockInTable">
    <thead class="table-light">
        <tr>
            <th width="5%">No</th>
            <th width="10%">Date</th>
            <th width="20%">Product Name</th>
            <th width="15%">Supplier</th>
            <th width="8%">Quantity</th>
            <th width="20%">Notes</th>
            <th width="10%" class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stockIns as $index => $stockIn)
        <tr>
            <td class="fw-bold">{{ $index + 1 }}</td>
            <td class="fw-bold">{{ date('d/m/Y', strtotime($stockIn->date)) }}</td>
            <td class="fw-bold">{{ $stockIn->product->name }}</td>
            <td class="fw-bold">{{ $stockIn->supplier->name }}</td>
            <td class="fw-bold">{{ $stockIn->quantity }}</td>
            <td class="fw-bold">{{ Str::limit($stockIn->notes, 50) }}</td>
            <td class="text-center">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="actionBtn{{ $stockIn->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="actionBtn{{ $stockIn->id }}">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editStockInModal"
                                data-id="{{ $stockIn->id }}"
                                data-reference_number="{{ $stockIn->reference_number }}"
                                data-date="{{ $stockIn->date }}"
                                data-product_id="{{ $stockIn->product_id }}"
                                data-supplier_id="{{ $stockIn->supplier_id }}"
                                data-quantity="{{ $stockIn->quantity }}"
                                data-notes="{{ $stockIn->notes }}">
                                <i class="fas fa-edit text-primary"></i> Edit
                            </button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteStockInModal"
                                data-id="{{ $stockIn->id }}">
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

                
                @if(count($stockIns) == 0)
                <div class="text-center p-5">
                    <i class="fas fa-dolly-flatbed fa-3x text-muted"></i>
                    <p class="mt-3">No incoming stock data available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Incoming Stock Modal -->
<div class="modal fade" id="addStockInModal" tabindex="-1" aria-labelledby="addStockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addStockInModalLabel"><i class="fas fa-plus-circle"></i> Add Incoming Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stock-ins.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="reference_number" name="reference_number" placeholder="Reference Number" required>
                                <label for="reference_number">Reference Number</label>
                            </div>
                        </div> -->
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" placeholder="Date" required>
                                <label for="date">Date</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="product_id" name="product_id" required>
                                    <option value="" selected disabled>Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <label for="product_id">Product</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="" selected disabled>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <label for="supplier_id">Supplier</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" placeholder="Initial Stock" value="0" min="0" required onkeypress="return event.charCode >= 48 && event.charCode <= 57" step="1">
                                <label for="quantity">Quantity</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control text-uppercase" id="notes" name="notes" rows="3" placeholder="Notes" style="height: 100px" oninput="this.value = this.value.toUpperCase();"></textarea>
                                <label for="notes">Notes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                       Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Incoming Stock Modal -->
<div class="modal fade" id="editStockInModal" tabindex="-1" aria-labelledby="editStockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editStockInModalLabel"><i class="fas fa-edit"></i> Edit Incoming Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStockInForm" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <input type="hidden" id="edit_id" name="id">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="edit_date" name="date" required>
                    <label for="edit_date">Date</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3">
                    <select class="form-select" id="edit_product_id" name="product_id" required>
                        <option value="" disabled>Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                        @endforeach
                    </select>
                    <label for="edit_product_id">Product</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3">
                    <select class="form-select" id="edit_supplier_id" name="supplier_id" required>
                        <option value="" disabled>Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    <label for="edit_supplier_id">Supplier</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="edit_quantity" name="quantity" min="1" required>
                    <label for="edit_quantity">Quantity</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3">
                    <textarea class="form-control text-uppercase" id="edit_notes" name="notes" rows="3" style="height: 100px"></textarea>
                    <label for="edit_notes">Notes</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
        </div>
    </div>
</div>

<!-- Delete Incoming Stock Modal -->
<div class="modal fade" id="deleteStockInModal" tabindex="-1" aria-labelledby="deleteStockInModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteStockInModalLabel"><i class="fas fa-trash-alt"></i> Delete Incoming Stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this incoming stock data?</p>
                <input type="hidden" id="delete_id" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <form id="deleteStockInForm" action="{{ route('stock-ins.destroy', 0) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('stock-ins.index') }}" method="GET">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filter Stock In by Date</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
          </div>
          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Filter</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editStockInModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const date = button.getAttribute('data-date');
        const product_id = button.getAttribute('data-product_id');
        const supplier_id = button.getAttribute('data-supplier_id');
        const quantity = button.getAttribute('data-quantity');
        const notes = button.getAttribute('data-notes');

        // Isi form
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_product_id').value = product_id;
        document.getElementById('edit_supplier_id').value = supplier_id;
        document.getElementById('edit_quantity').value = quantity;
        document.getElementById('edit_notes').value = notes;

        // Ganti action form
        const form = document.getElementById('editStockInForm');
        form.action = `/stock-ins/${id}`; // Pastikan route resource ada
    });
});
</script>
@endsection
