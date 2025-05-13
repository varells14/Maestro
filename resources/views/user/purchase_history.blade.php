@extends('layouts.app')

@section('content')
<style>
/* Add these styles to your CSS file or in a style tag if not already added */
.purchase-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 12px;
}

.purchase-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.fs-7 {
    font-size: 0.85rem;
}

.fw-medium {
    font-weight: 500;
}

.rounded-pill {
    border-radius: 50rem;
}

.empty-state {
    border: 2px dashed #dee2e6;
}
</style>
<div class="container-fluid my-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h3 class="m-0 font-weight-bold">Purchase Orders History</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> Filter Purchase Orders Request
                </button>
                
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

            <div class="mb-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search requests...">
            </div>

            <div class="row" id="purchasesContainer">
    @forelse($request as $purchase)
        @php
            // Check first if the purchase has a final approval status (approved or rejected)
            if ($purchase->status === 'approved') {
                $statusBadge = 'success';
                $statusText = 'Approved';
            } 
            elseif ($purchase->status === 'rejected') {
                $statusBadge = 'danger';
                $statusText = 'Rejected';
            }
            // If approved field contains values
            elseif ($purchase->approved !== null) {
                if (Str::contains(strtolower($purchase->approved), 'approved')) {
                    $statusBadge = 'success';
                    $statusText = 'Approved';
                } 
                elseif (Str::contains(strtolower($purchase->approved), 'reject')) {
                    $statusBadge = 'danger';
                    $statusText = 'Rejected';
                }
                else {
                    $statusBadge = 'info';
                    $statusText = 'Waiting Approval';
                }
            }
            // If checker value contains approved or rejected but no approval yet
            elseif (Str::contains(strtolower($purchase->checker ?? ''), ['approved', 'rejected'])) {
                $statusBadge = 'info';
                $statusText = 'Waiting Approval';
            }
            // Check if checker value is null, pending, or waiting
            elseif ($purchase->checker === null || 
                strtolower($purchase->checker ?? '') === 'pending' || 
                strtolower($purchase->checker ?? '') === 'waiting') {
                $statusBadge = 'warning';
                $statusText = 'Waiting Check';
            }
            // Fallback to standard status mapping
            else {
                $statusBadge = match($purchase->status) {
                    'approved' => 'success',
                    'pending' => 'warning',
                    'rejected' => 'danger',
                    default => 'secondary',
                };
                $statusText = ucfirst($purchase->status ?? 'Pending');
            }

            $priorityColor = match($purchase->priority) {
                'HIGH' => 'danger',
                'MEDIUM' => 'warning',
                'LOW' => 'info',
                'URGENT' => 'dark',
                default => 'secondary',
            };

            $purchaseNumber = $purchase->purchase_number ?? 'PO-' . str_pad($purchase->id, 4, '0', STR_PAD_LEFT);
        @endphp
        <div class="col-md-6 col-lg-3 mb-2 purchase-card-wrapper">
                <div class="card h-100 border-1 border-primary shadow-sm purchase-card" data-id="{{ $purchase->id }}">
                    <!-- Colored header based on status -->
                    <div class="card-header bg-{{ $statusBadge }} bg-opacity-10 border-bottom-0 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 text-truncate fw-bold" title="{{ $purchase->purchase_name }}">
                                {{ $purchase->purchase_name }}
                            </h6>
                            <span class="badge bg-{{ $statusBadge }} small py-1 px-2 rounded-pill">{{ $statusText }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body pt-2 pb-2">
                        <!-- Purchase Number with improved styling -->
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    <i class="fas fa-hashtag text-primary small"></i>
                                </div>
                                <span class="small fw-bold">{{ $purchaseNumber }}</span>
                            </div>
                            <span class="badge bg-{{ $priorityColor }} small py-1 px-2">{{ $purchase->priority ?? 'Normal' }}</span>
                        </div>
                        
                        <!-- Project info with icon -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                <i class="fas fa-tag text-primary small"></i>
                            </div>
                            <div>
                                <span class="text-muted small">Project</span>
                                <p class="mb-0 small fw-medium">{{ $purchase->project }}</p>
                            </div>
                        </div>
                        
                        <!-- Date info with icon -->
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                <i class="fas fa-calendar-alt text-primary small"></i>
                            </div>
                            <div>
                                <span class="text-muted small">Purchase Date</span>
                                <p class="mb-0 small fw-medium">{{ $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light d-flex justify-content-end border-top py-2">
                        <button class="btn btn-primary btn-sm view-details rounded-pill px-3 shadow-sm" data-id="{{ $purchase->id }}">
                            <i class="fas fa-eye me-1"></i> View
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-3">
                <div class="empty-state p-3 rounded-3 bg-light">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted fw-bold">No Purchase Orders Found</h5>
                    <p class="text-muted small mb-2">Click "Create Purchase Order" to add a new entry</p>
                    <button class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fas fa-plus me-1"></i> Create Purchase Order
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Request Modal -->
<div class="modal fade" id="addRequestModal" tabindex="-1" aria-labelledby="addRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addPurchaseModalLabel">Create New Purchase Order</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form id="purchaseForm" action="{{ route('user.purchase_request.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="purchase_name" class="form-label">Purchase Order Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase" id="purchase_name" name="purchase_name" oninput="this.value = this.value.toUpperCase();" required>
                </div>
                <div class="col-md-6">
                    <label for="purchase_number" class="form-label">Purchase Order Number</label>
                    <input type="text" class="form-control" id="purchase_number" name="purchase_number" placeholder="Automatically Generated" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="LOW">LOW</option>
                        <option value="MEDIUM" selected>MEDIUM</option>
                        <option value="HIGH">HIGH</option>
                        <option value="URGENT">URGENT</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Items <span class="text-danger">*</span></label>
                <div id="items-container">
                    <div class="row mb-2 item-row">
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="items[0][product]" placeholder="Item Name" oninput="this.value = this.value.toUpperCase();" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="items[0][quantity]" placeholder="Quantity" min="1" value="1" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-item-btn" disabled>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-info mt-2" id="add-item-btn">
                    <i class="fas fa-plus"></i> Add Another Item
                </button>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" oninput="this.value = this.value.toUpperCase();"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit Purchase Order</button>
        </div>
    </form>
</div>

    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewDetailsModalLabel">Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="requestDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading request details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('user.material_request') }}" method="GET">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filter Material Request by Date</h5>
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

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease;
    cursor: pointer;
}

.request-card {
    transition: all 0.3s ease;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add Item Button
    let itemIndex = 0;
    document.getElementById('add-item-btn').addEventListener('click', function () {
        itemIndex++;
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 item-row';
        newRow.innerHTML = `
            <div class="col-md-7">
                <input type="text" class="form-control" name="items[${itemIndex}][product]" placeholder="Item Name" oninput="this.value = this.value.toUpperCase();" required>
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" name="items[${itemIndex}][quantity]" placeholder="Quantity" min="1" value="1" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-item-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.getElementById('items-container').appendChild(newRow);

        newRow.querySelector('.remove-item-btn').addEventListener('click', function () {
            this.closest('.item-row').remove();
        });
    });

    // Search filter
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.purchase-card-wrapper');

        cards.forEach(function (card) {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Modal Detail Logic
    const viewDetailsButtons = document.querySelectorAll('.view-details');
    const viewDetailsModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));

    function openDetailsModal(requestId) {
        document.getElementById('requestDetailsContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading request details...</p>
            </div>
        `;
        viewDetailsModal.show();

        fetch("{{ url('purchase/request') }}/" + requestId + "/details")
            .then(response => response.text())
            .then(data => {
                document.getElementById('requestDetailsContent').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('requestDetailsContent').innerHTML =
                    '<div class="alert alert-danger">Error loading request details. Please try again.</div>';
            });
    }

    // Click on "Details" button
    viewDetailsButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const requestId = this.getAttribute('data-id');
            openDetailsModal(requestId);
        });
    });

    // Click on entire card
    document.querySelectorAll('.purchase-card').forEach(function (card) {
        card.addEventListener('click', function () {
            const requestId = this.getAttribute('data-id');
            openDetailsModal(requestId);
        });
    });
});
</script>

@endsection