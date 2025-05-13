{{-- Improved Material Request Details Modal --}}
<div class="request-details">
    {{-- Request Information Card --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Request Information</h5>
                <a href="{{ route('user.material_request.export', $request->id) }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-file-arrow-down fa-lg"></i> Eksport Request
                </a>
                
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0 text-primary" style="width: 150px;">
                            <strong>Request Number:</strong>
                        </div>
                        <div class="flex-grow-1">
                            {{ $request->request_number ?? 'MR-'.str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    @php
                        $priorityColor = match($request->priority) {
                            'High' => 'danger',
                            'Medium' => 'warning',
                            'Low' => 'info',
                            'Urgent' => 'dark',
                            default => 'success'
                        };
                    @endphp
                    <div class="d-flex">
                        <div class="flex-shrink-0 text-primary" style="width: 150px;">
                            <strong>Priority:</strong>
                        </div>
                        <div class="flex-grow-1">
                            <span class="badge bg-{{ $priorityColor }} rounded-pill px-3">{{ $request->priority ?? 'Normal' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0 text-primary" style="width: 150px;">
                            <strong>Request Name:</strong>
                        </div>
                        <div class="flex-grow-1">
                            {{ $request->request_name }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    @php
                        $statusColor = match($request->status) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <div class="d-flex">
                        <div class="flex-shrink-0 text-primary" style="width: 150px;">
                            <strong>Status:</strong>
                        </div>
                        <div class="flex-grow-1">
                            <span class="badge bg-{{ $statusColor }} rounded-pill px-3">{{ ucfirst($request->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex">
                        <div class="flex-shrink-0 text-primary" style="width: 150px;">
                            <strong>Request Date:</strong>
                        </div>
                        <div class="flex-grow-1">
                            {{ $request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d M Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    @php
                        $approvedColor = match($request->approved) {
                            'approved' => 'success',
                            'waiting' => 'warning',
                            'rejected' => 'danger',
                            default => 'secondary'
                        };
                    @endphp
                    <div class="d-flex">
                        <div class="flex-shrink-0 text-primary" style="width: 150px;">
                            <strong>Project:</strong>
                        </div>
                        <div class="flex-grow-1">
                            <span>{{ ($request->project) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-12">
                    <div class="alert alert-light border">
                        <h6 class="text-primary mb-2"><i class="fas fa-sticky-note me-2"></i>Notes:</h6>
                        <p class="mb-0">{{ $request->notes ?? 'No notes provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Approval Information Card --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Approval Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3 border-light">
                        <div class="card-body">
                            <h6 class="card-title text-info"><i class="fas fa-check-circle me-2"></i>Checker</h6>
                            <hr>
                            <div class="mb-2">
                                <strong>Checked By:</strong><br>
                                {{ in_array($request->checker, ['pending', 'waiting', null]) ? 'Not checked yet' : $request->checker }}
                            </div>
                            <div>
                                <strong>Checked At:</strong><br>
                                {{ $request->checker_at ? \Carbon\Carbon::parse($request->checker_at)->format('d M Y, H:i') : 'Not checked yet' }}
                            </div>
                            @if(strpos($request->checker, 'rejected') !== false && $request->checker_rejected_notes)
                            <div class="mt-2 alert alert-danger">
                                <strong>Rejection Notes:</strong><br>
                                {{ $request->checker_rejected_notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3 border-light">
                        <div class="card-body">
                            <h6 class="card-title text-info"><i class="fas fa-stamp me-2"></i>Approver</h6>
                            <hr>
                            <div class="mb-2">
                                <strong>Approved By:</strong><br>
                                {{ in_array($request->approved, ['pending', 'waiting', null]) ? 'Not approved yet' : $request->approved }}
                            </div>
                            <div>
                                <strong>Approved At:</strong><br>
                                {{ $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->format('d M Y, H:i') : 'Not approved yet' }}
                            </div>
                            @if(strpos($request->approved, 'rejected') !== false && $request->approved_rejected_notes)
                            <div class="mt-2 alert alert-danger">
                                <strong>Rejection Notes:</strong><br>
                                {{ $request->approved_rejected_notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Materials List Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Requested Materials</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="8%" class="text-center">No</th>
                            <th width="62%">Material</th>
                            <th width="30%" class="text-center">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($request->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->product }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No materials found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- HTML for Modal Footer with Approval Buttons --}}
<div id="approval-buttons" class="mt-3">
    @if(auth()->user()->user_nik == '124000003' && $request->status == 'pending' && $request->checker == 'pending')
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="checkerActionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="approve-tab" data-bs-toggle="tab" data-bs-target="#approve-content" type="button" role="tab" aria-controls="approve-content" aria-selected="true">Approve</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reject-tab" data-bs-toggle="tab" data-bs-target="#reject-content" type="button" role="tab" aria-controls="reject-content" aria-selected="false">Reject</button>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3 bg-light" id="checkerActionTabsContent">
                    <div class="tab-pane fade show active" id="approve-content" role="tabpanel" aria-labelledby="approve-tab">
                        <div class="text-center py-2">
                            <form action="{{ route('user.material_request.checker.approve', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Confirm Approval
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reject-content" role="tabpanel" aria-labelledby="reject-tab">
                        <form action="{{ route('user.material_request.checker.reject', $request->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="checker_rejected_notes" class="form-label">Rejection Notes</label>
                                <textarea class="form-control text-uppercase" id="checker_rejected_notes" name="checker_rejected_notes" rows="3" required placeholder="Please provide a reason for rejection..." oninput="this.value = this.value.toUpperCase();"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i> Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(auth()->user()->user_nik == '12345678' && $request->status == 'pending' && Str::contains(strtolower($request->checker), 'approved'))
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="approverActionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="final-approve-tab" data-bs-toggle="tab" data-bs-target="#final-approve-content" type="button" role="tab" aria-controls="final-approve-content" aria-selected="true">Final Approve</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="final-reject-tab" data-bs-toggle="tab" data-bs-target="#final-reject-content" type="button" role="tab" aria-controls="final-reject-content" aria-selected="false">Reject</button>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3 bg-light" id="approverActionTabsContent">
                    <div class="tab-pane fade show active" id="final-approve-content" role="tabpanel" aria-labelledby="final-approve-tab">
                        <div class="text-center py-2">
                            <form action="{{ route('user.material_request.approver.approve', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Confirm Final Approval
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="final-reject-content" role="tabpanel" aria-labelledby="final-reject-tab">
                        <form action="{{ route('user.material_request.approver.reject', $request->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="approved_rejected_notes" class="form-label">Rejection Notes</label>
                                <textarea class="form-control text-uppercase" id="approved_rejected_notes" name="approved_rejected_notes" rows="3" required placeholder="Please provide a reason for rejection..." oninput="this.value = this.value.toUpperCase();"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i> Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>