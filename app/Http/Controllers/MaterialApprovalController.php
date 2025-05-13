<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MaterialApprovalController extends Controller
{
    public function approval()
    {
        $query = MaterialRequest::with('items')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc');
    
        // Ambil filter dari request
        $startDate = request('start_date');
        $endDate = request('end_date');
    
        // Terapkan filter jika tersedia
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }
        $approval = $query->get();
    
        return view('user.material_approval', compact('approval', 'startDate', 'endDate'));
    }

    // CHECKER APPROVE
    public function checkerApprove($id)
    {
        $request = MaterialRequest::findOrFail($id);
        $request->checker = Auth::user()->user_fullname . ' (approved)';
        $request->checker_at = now();
        $request->approved = 'waiting'; // trigger tahap approver
        $request->save();

        return redirect()->back()->with('success', 'Request approved by checker.');
    }

    // CHECKER REJECT
    public function checkerReject($id, Request $request)
    {
        $materialRequest = MaterialRequest::findOrFail($id);
        $materialRequest->checker = Auth::user()->user_fullname . ' (rejected)';
        $materialRequest->checker_at = now();
        $materialRequest->status = 'rejected';
        $materialRequest->checker_rejected_notes = $request->checker_rejected_notes;
        $materialRequest->save();
    
        return redirect()->back()->with('error', 'Request rejected by checker.');
    }
    

    // APPROVER APPROVE
    public function approverApprove($id)
    {
        $request = MaterialRequest::findOrFail($id);
        $request->approved =  Auth::user()->user_fullname . ' (approved)';
        $request->approved_at = now();
        $request->approved =  Auth::user()->user_fullname . ' (approved)';
        $request->status = 'approved';
        $request->save();

        return redirect()->back()->with('success', 'Request fully approved.');
    }

    // APPROVER REJECT
    public function approverReject($id, Request $request)
    {
        $materialRequest = MaterialRequest::findOrFail($id);
        $materialRequest->approved = Auth::user()->user_fullname . ' (rejected)';
        $materialRequest->approved_at = now();
        $materialRequest->approved = 'rejected';
        $materialRequest->status = 'rejected';
        $materialRequest->approved_rejected_notes = $request->approved_rejected_notes;
        $materialRequest->save();
    
        return redirect()->back()->with('error', 'Request rejected by approver.');
    }
}
