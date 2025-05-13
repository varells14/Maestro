@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h3 class="m-0 font-weight-bold">Materials Request Approval</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Filter Approval Request
            </button>
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
                <input type="text" id="searchInput" class="form-control" placeholder="Search approval requests...">
            </div>

            <div class="row" id="approvalsContainer">
                @forelse($approval as $item)
                    @php
                        $statusColor = match($item->status) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'success'
                        };
                        
                        $priorityColor = match($item->priority) {
                            'High' => 'danger',
                            'Medium' => 'warning',
                            'Low' => 'info',
                            'Urgent' => 'dark',
                            default => 'success'
                        };

                        $requestNumber = $item->request_number ?? 'MR-'.str_pad($item->id, 4, '0', STR_PAD_LEFT);
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4 approval-card-wrapper">
                        <div class="card h-100 border-top border-3 border-primary shadow-sm hover-shadow approval-card" data-id="{{ $item->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0 text-truncate" title="{{ $item->request_name }}">
                                        {{ $item->request_name }}
                                    </h5>
                                    <span class="badge bg-{{ $statusColor }}">{{ ucfirst($item->status) }}</span>
                                </div>
                                <div class="card-text">
                                    <div class="d-flex justify-content-between mb-2">   
                                        <span class="text-muted"><i class="fas fa-hashtag me-1"></i> {{ $requestNumber }}</span>
                                        <span class="badge bg-{{ $priorityColor }}">{{ $item->priority ?? 'Normal' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                        <span>{{ $item->request_date ? \Carbon\Carbon::parse($item->request_date)->format('d-m-Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent d-flex justify-content-end">
                                <button class="btn btn-primary btn-sm view-details" data-id="{{ $item->id }}">
                                    <i class="fas fa-eye me-1"></i> Details
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No approval requests found</h5>
                        <p class="text-muted">There are no material requests pending approval at this time</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('user.material_approval') }}" method="GET">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filter Material Request Approval by Date</h5>
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

.approval-card {
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const approvalCards = document.querySelectorAll('.approval-card-wrapper');
        
        approvalCards.forEach(function(card) {
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
    document.querySelectorAll('.approval-card').forEach(function(card) {
        card.addEventListener('click', function() {
            const requestId = this.getAttribute('data-id');
            openDetailsModal(requestId);
        });
    });
});
</script>
@endsection