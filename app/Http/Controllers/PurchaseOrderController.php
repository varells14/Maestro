<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrderItem;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Color;

class PurchaseOrderController extends Controller
{
     
    public function request()
    {
        $query = PurchaseOrder::with('items')
        ->where('status', 'pending')
            
            ->orderBy('created_at', 'desc');

        $request = $query->get();
        $project = Project::all();

        
        return view('user.purchase_request', compact('request','project'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'project' => 'required|string|max:255',
            'purchase_name' => 'required|string|max:255',
            'priority' => 'required|string|in:LOW,MEDIUM,HIGH,URGENT',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    
        // Generate purchase number if not provided
        $purchaseNumber = $request->purchase_number;
        if (empty($purchaseNumber)) {
            $latestPurchase = \App\Models\PurchaseOrder::latest('id')->first();
            $nextId = $latestPurchase ? $latestPurchase->id + 1 : 1;
            $purchaseNumber = 'PO-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
    
        try {
            // Create main purchase order
            $purchaseOrder = \App\Models\PurchaseOrder::create([
                'project' => $request->project,
                'purchase_number' => $purchaseNumber,
                'purchase_name' => $request->purchase_name,
                'priority' => $request->priority,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
                'status' => 'pending',
                'checker' => 'pending',
                'approved' => 'waiting',
            ]);
    
            // Insert each item
            foreach ($request->items as $item) {
                \App\Models\PurchaseOrderItem::create([
                    'purchase_id' => $purchaseOrder->id,
                    'product' => $item['product'],
                    'quantity' => $item['quantity'],
                ]);
            }
    
            return redirect()->route('user.purchase_request')->with('success', 'Purchase order submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.purchase_request')->with('error', 'Failed to submit purchase order: ' . $e->getMessage());
        }
    }

    public function getRequestDetails($id)
    {
        $request = PurchaseOrder::with('items')->findOrFail($id);
        return view('user.purchase_request_details', compact('request'));

    }

    







    public function approval()
    {
        return view('user.purchase_approval');
    }





    public function checkerApprove($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->checker = Auth::user()->user_fullname . ' (approved)';
        $order->checker_at = now();
        $order->approved = 'waiting'; // lanjut ke approver
        $order->save();
    
        return redirect()->back()->with('success', 'Purchase order approved by checker.');
    }
    
    // CHECKER REJECT
    public function checkerReject($id, Request $request)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->checker = Auth::user()->user_fullname . ' (rejected)';
        $order->checker_at = now();
        $order->status = 'rejected';
        $order->checker_rejected_notes = $request->checker_rejected_notes;
        $order->save();
    
        return redirect()->back()->with('error', 'Purchase order rejected by checker.');
    }
    
    // APPROVER APPROVE
    public function approverApprove($id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->approved = Auth::user()->user_fullname . ' (approved)';
        $order->approved_at = now();
        $order->status = 'approved';
        $order->save();
    
        return redirect()->back()->with('success', 'Purchase order fully approved.');
    }
    
    // APPROVER REJECT
    public function approverReject($id, Request $request)
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->approved = Auth::user()->user_fullname . ' (rejected)';
        $order->approved_at = now();
        $order->status = 'rejected';
        $order->approved_rejected_notes = $request->approved_rejected_notes;
        $order->save();
    
        return redirect()->back()->with('error', 'Purchase order rejected by approver.');
    }

    public function history()
    {

        $query = PurchaseOrder::with('items')
        ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('created_at', 'desc');

        $request = $query->get();

        
       
    
        return view('user.purchase_history', compact('request'));
    }


    
}
