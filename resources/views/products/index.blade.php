@extends('layouts.app')

@section('content')
<div class="container-fluid my-38">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h3 class="m-0 font-weight-bold">Stock Materials</h3>
            <div class="d-flex align-items-center">
            <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus"></i> Add Materials
            </button>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-light fa-file-arrow-down"></i> Report
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('categories.index', ['format' => 'excel']) }}">
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
                <table class="table table-hover table-striped align-middle" id="productTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                
                            <th width="20%">Name</th>
                           
                            <th width="10%">Stock</th>
                            <th width="25%">Description</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                        <tr>
                            <td class="fw-bold">{{ $index + 1 }}</td>
                          
                            <td class="fw-bold">{{ $product->name }}</td>
                          
                            <td class="fw-bold">{{ $product->stock }}</td>
                            <td class="fw-bold">{{ Str::limit($product->description, 50) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                                <i class="fas fa-edit text-info"></i> Edit
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->id }}">
                                                <i class="fas fa-trash text-danger"></i> Delete
                                            </button>
                                        </li>
                                     
                                       
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if(count($products) == 0)
                <div class="text-center p-5">
                    <i class="fas fa-box-open fa-3x text-muted"></i>
                    <p class="mt-3">No product data available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addProductLabel"><i class="fas fa-plus-circle"></i> Add Material</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="code" class="form-control" id="codeInput" placeholder="Product Code">
                            <label for="codeInput">Product Code</label>
                        </div>
                    </div> -->
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control text-uppercase" id="nameInput" placeholder="Product Name" oninput="this.value = this.value.toUpperCase()">
                            <label for="nameInput">Product Name</label>
                        </div>
                    </div>
                  
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="number" name="stock" class="form-control" id="stockInput" placeholder="Initial Stock" value="0" min="0" required onkeypress="return event.charCode >= 48 && event.charCode <= 57" step="1">
                            <label for="stockInput">Initial Stock</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <textarea name="description" class="form-control text-uppercase" placeholder="Description" id="descInput" style="height: 100px" oninput="this.value = this.value.toUpperCase()"></textarea>
                            <label for="descInput">Description</label>
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

<!-- Edit & Delete Modals For Each Product -->
@foreach($products as $product)
    <!-- Edit Modal -->
    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="editLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editLabel{{ $product->id }}">
                        <i class="fas fa-edit"></i> Edit Material: {{ $product->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" name="code" class="form-control" id="codeEdit{{ $product->id }}" placeholder="Product Code" value="{{ $product->code }}" required>
                                <label for="codeEdit{{ $product->id }}">Product Code</label>
                            </div>
                        </div> -->
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control text-uppercase" id="nameEdit{{ $product->id }}" placeholder="Product Name" value="{{ $product->name }}" required oninput="this.value = this.value.toUpperCase()">
                                <label for="nameEdit{{ $product->id }}">Product Name</label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="number" name="stock" class="form-control" id="stockEdit{{ $product->id }}" placeholder="Stock" value="{{ $product->stock }}" min="0" required>
                                <label for="stockEdit{{ $product->id }}">Stock</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <textarea name="description" class="form-control text-uppercase" placeholder="Description" id="descEdit{{ $product->id }}" style="height: 100px" oninput="this.value = this.value.toUpperCase()">{{ $product->description }}</textarea>
                                <label for="descEdit{{ $product->id }}">Description</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary text-white">
                    Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('products.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteLabel{{ $product->id }}">
                        <i class="fas fa-exclamation-triangle"></i> Delete Confirmation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <p class="mb-0">Are you sure you want to delete this item:</p>
                    <h4 class="text-danger mt-2">{{ $product->name }} ({{ $product->code }})</h4>
                    <p class="text-muted small">This action cannot be undone!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
