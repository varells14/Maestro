@extends('layouts.app')

@section('content')

<style>
/* Add these styles to your CSS file or in a style tag if not already added */
.history-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 12px;
}

.history-card:hover {
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
            <h3 class="m-0 font-weight-bold">History Materials Request</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Filter History Request
            </button>
        </div>

        <div class="card-body py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search history requests...">
    </div>

    <div class="row g-2" id="historyContainer">
        @forelse($history as $item)
            @php
                // Priority color logic
                $priorityColor = match($item->priority) {
                    'High' => 'danger',
                    'Medium' => 'warning',
                    'Low' => 'info',
                    'Urgent' => 'dark',
                    default => 'success'
                };

                // Check first if the item has a final approval status (approved or rejected)
                // and prioritize showing that over intermediate steps
                if ($item->status === 'approved') {
                    $statusBadge = 'success';
                    $statusText = 'Approved';
                } 
                elseif ($item->status === 'rejected') {
                    $statusBadge = 'danger';
                    $statusText = 'Rejected';
                }
                // If approved field contains values
                elseif ($item->approved !== null) {
                    if (Str::contains(strtolower($item->approved), 'approved')) {
                        $statusBadge = 'success';
                        $statusText = 'Approved';
                    } 
                    elseif (Str::contains(strtolower($item->approved), 'reject')) {
                        $statusBadge = 'danger';
                        $statusText = 'Rejected';
                    }
                    else {
                        $statusBadge = 'info';
                        $statusText = 'Waiting Approval';
                    }
                }
                // If checker value contains approved or rejected but no approval yet
                elseif (Str::contains(strtolower($item->checker ?? ''), ['approved', 'rejected'])) {
                    $statusBadge = 'info';
                    $statusText = 'Waiting Approval';
                }
                // Check if checker value is null, pending, or waiting
                elseif ($item->checker === null || 
                    strtolower($item->checker ?? '') === 'pending' || 
                    strtolower($item->checker ?? '') === 'waiting') {
                    $statusBadge = 'warning';
                    $statusText = 'Waiting Check';
                }
                // Fallback to default pending
                else {
                    $statusBadge = 'secondary';
                    $statusText = 'Pending';
                }

                $requestNumber = $item->request_number ?? 'MR-'.str_pad($item->id, 4, '0', STR_PAD_LEFT);
            @endphp
            <div class="col-md-6 col-lg-3 mb-2 history-card-wrapper">
                <div class="card h-100 border-1 border-primary shadow-sm history-card" data-id="{{ $item->id }}">
                    <!-- Colored header based on status -->
                    <div class="card-header bg-{{ $statusBadge }} bg-opacity-10 border-bottom-0 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 text-truncate fw-bold" title="{{ $item->request_name }}">
                                {{ $item->request_name }}
                            </h6>
                            <span class="badge bg-{{ $statusBadge }} small py-1 px-2 rounded-pill">{{ $statusText }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body pt-2 pb-2">
                        <!-- Request Number with improved styling -->
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                    <i class="fas fa-hashtag text-primary small"></i>
                                </div>
                                <span class="small fw-bold">{{ $requestNumber }}</span>
                            </div>
                            <span class="badge bg-{{ $priorityColor }} small py-1 px-2">{{ $item->priority ?? 'Normal' }}</span>
                        </div>
                        
                        <!-- Project info with icon -->
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                <i class="fas fa-tag text-primary small"></i>
                            </div>
                            <div>
                                <span class="text-muted small">Project</span>
                                <p class="mb-0 small fw-medium">{{ $item->project }}</p>
                            </div>
                        </div>
                        
                        <!-- Date info with icon -->
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                <i class="fas fa-calendar-alt text-primary small"></i>
                            </div>
                            <div>
                                <span class="text-muted small">Request Date</span>
                                <p class="mb-0 small fw-medium">{{ $item->request_date ? \Carbon\Carbon::parse($item->request_date)->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light d-flex justify-content-end border-top py-2">
                        <button class="btn btn-primary btn-sm view-details rounded-pill px-3 shadow-sm" data-id="{{ $item->id }}">
                            <i class="fas fa-eye me-1"></i> View
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-3">
                <div class="empty-state p-3 rounded-3 bg-light">
                    <i class="fas fa-history fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted fw-bold">No History Requests Found</h5>
                    <p class="text-muted small mb-2">There are no material requests in the history at this time</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('user.material_history') }}" method="GET">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filter Material Request History by Date</h5>
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

<!-- Add Request Modal -->
<div class="modal fade" id="addRequestModal" tabindex="-1" aria-labelledby="addRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addRequestModalLabel">Request New Material</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="requestForm" action="{{ route('user.material_request.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="request_name" class="form-label">Request Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="request_name" name="request_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="request_number" class="form-label">Request Number</label>
                            <input type="text" class="form-control" id="request_number" name="request_number" placeholder="Will be generated if empty">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="request_date" class="form-label">Request Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="request_date" name="request_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Materials <span class="text-danger">*</span></label>
                        <div id="materials-container">
                            <div class="row mb-2 material-item">
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="items[0][product]" placeholder="Material Name" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" name="items[0][quantity]" placeholder="Quantity" min="1" value="1" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger remove-material-btn" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-info mt-2" id="add-material-btn">
                            <i class="fas fa-plus"></i> Add Another Material
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
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

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease;
    cursor: pointer;
}

.history-card {
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Material row functionality
    let materialIndex = 0;
    
    document.getElementById('add-material-btn')?.addEventListener('click', function() {
        materialIndex++;
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 material-item';
        newRow.innerHTML = `
            <div class="col-md-7">
                <input type="text" class="form-control" name="items[${materialIndex}][product]" placeholder="Material Name" required>
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" name="items[${materialIndex}][quantity]" placeholder="Quantity" min="1" value="1" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-material-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.getElementById('materials-container').appendChild(newRow);
        
        // Add event listener to the new remove button
        newRow.querySelector('.remove-material-btn').addEventListener('click', function() {
            this.closest('.material-item').remove();
        });
    });
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const historyCards = document.querySelectorAll('.history-card-wrapper');
        
        historyCards.forEach(function(card) {
            const cardText = card.textContent.toLowerCase();
            if (cardText.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // View Details Modal
    const viewDetailsButtons = document.querySelectorAll('.view-details');
    const viewDetailsModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
    
    // Function to open modal with request details
    function openDetailsModal(requestId) {
        // Show loading spinner
        document.getElementById('requestDetailsContent').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading request details...</p>
            </div>
        `;
        
        // Show the modal
        viewDetailsModal.show();
        
        // Fetch request details
        fetch('{{ route('user.material_request.details', '') }}/' + requestId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('requestDetailsContent').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('requestDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Error loading request details. Please try again.</div>';
            });
    }
    
    // Add click event to both the cards and the details buttons
    viewDetailsButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent card click event from firing
            const requestId = this.getAttribute('data-id');
            openDetailsModal(requestId);
        });
    });
    
    // Make entire card clickable
    document.querySelectorAll('.history-card').forEach(function(card) {
        card.addEventListener('click', function() {
            const requestId = this.getAttribute('data-id');
            openDetailsModal(requestId);
        });
    });
});
</script>
@endsection