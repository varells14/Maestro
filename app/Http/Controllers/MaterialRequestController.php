<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Project;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Color;

class MaterialRequestController extends Controller
{
    public function request(Request $request)
    {
        $query = MaterialRequest::with('items')
        ->where('status', 'pending')
                    ->orderBy('created_at', 'desc');
    
        // Ambil tanggal dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Terapkan filter tanggal jika ada
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
    
        // Ambil data setelah filter
        $requests = $query->get();
    
        // Cek jika ada permintaan export
        if ($request->has('format')) {
            return $this->exportData($request->format, $requests);
        }

        $project= Project::all();
    
        return view('user.material_request', compact('requests', 'startDate', 'endDate', 'project'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'request_name' => 'required|string|max:255',
            'priority' => 'required|string|in:LOW,MEDIUM,HIGH,URGENT',
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
       
        $requestNumber = $request->request_number;
        if (empty($requestNumber)) {
            $latestRequest = MaterialRequest::latest('id')->first();
            $nextId = $latestRequest ? $latestRequest->id + 1 : 1;
            $requestNumber = 'MR-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
        
        try {
             
            $materialRequest = MaterialRequest::create([
                'project' => $request->project,
                'request_number' => $requestNumber,
                'request_name' => $request->request_name,
                'priority' => $request->priority,
                'request_date' => $request->request_date,
                'notes' => $request->notes,
                'status' => 'pending',  
                'checker' => 'pending',
                'approved' => 'waiting',
                
            ]);
            
            // Add the material items
            foreach ($request->items as $item) {
                MaterialRequestItem::create([
                    'request_id' => $materialRequest->id,
                    'product' => $item['product'],
                    'quantity' => $item['quantity'],
                ]);
            }
            
            return redirect()->route('user.material_request')->with('success', 'Material request submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.material_request')->with('error', 'Failed to submit material request: ' . $e->getMessage());
        }
    }

    
    public function destroy($id)
    {
        try {
            $materialRequest = MaterialRequest::findOrFail($id);
            $materialRequest->delete();
            return redirect()->route('user.material_request')->with('success', 'Material request deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.material_request')->with('error', 'Failed to delete material request: ' . $e->getMessage());
        }
    }

    
    
    public function getRequestDetails($id)
    {
        $request = MaterialRequest::with('items')->findOrFail($id);
        return view('user.material_request_details', compact('request'));
    }
    
   
    
    public function exportToExcel($id)
{
    $request = MaterialRequest::with('items')->findOrFail($id);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set column width
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(35);
    $sheet->getColumnDimension('C')->setWidth(25);
    $sheet->getColumnDimension('D')->setWidth(25);
    $sheet->getColumnDimension('E')->setWidth(15);
    $sheet->getColumnDimension('F')->setWidth(20);
    
    // ===== HEADER SECTION =====
    // Add logo in a separate area to avoid overlap
    try {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('assets/images/mes.png'));
        $drawing->setCoordinates('A2');
        $drawing->setWidth(80); // Control size better with fixed width
        $drawing->setHeight(80);
        $drawing->setOffsetX(5); // Add margins
        $drawing->setOffsetY(5);
        $drawing->setWorksheet($sheet);
    } catch (\Exception $e) {
        // Logo optional - don't stop if there's an issue
    }
    
    // Company header - moved to the right to avoid logo overlap
    $sheet->mergeCells('C2:F2');
    $sheet->setCellValue('C2', 'PT. KINARYA MAESTRO NUSANTARA');
    $sheet->getStyle('C2')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $sheet->mergeCells('C3:F3');
    $sheet->setCellValue('C3', 'Jl. Tupai Raya PGA No.13, RT.01/RW.07, Meruyung');
    $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $sheet->mergeCells('C4:F4');
    $sheet->setCellValue('C4', 'Kec. Limo, Kota Depok, Jawa Barat 16515');
    $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $sheet->mergeCells('C5:F5');
    $sheet->setCellValue('C5', 'Phone: +62812-8721-6516 | Email: maestro@contractor.com');
    $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // Create some space between header and title
    $sheet->getRowDimension(6)->setRowHeight(10);
    
    // Add horizontal line with better padding
    $sheet->getStyle('A7:F7')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
    
    // Space after the line
    $sheet->getRowDimension(8)->setRowHeight(10);
    
    // ===== TITLE SECTION =====
    $sheet->mergeCells('A9:F9');
    $sheet->setCellValue('A9', 'MATERIAL REQUEST DETAILS');
    $sheet->getStyle('A9')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E7EFFA');
    $sheet->getStyle('A9:F9')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getRowDimension(9)->setRowHeight(25); // Better height for title
    
    // Space after title
    $sheet->getRowDimension(10)->setRowHeight(8);
    
    // ===== REQUEST INFO SECTION =====
    // Style info box
    $sheet->getStyle('A11:F15')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9F9F9');
    $sheet->getStyle('A11:F15')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    // Request info fields with better spacing
    $sheet->setCellValue('B11', 'Request Number:');
    $sheet->setCellValue('C11', $request->request_number);
    $sheet->getStyle('B11')->getFont()->setBold(true);
    
    $sheet->setCellValue('E11', 'Status:');
    $sheet->setCellValue('F11', ucfirst($request->status));
    $sheet->getStyle('E11')->getFont()->setBold(true);
    
    // Color for status
    if (!empty($request->status)) {
        $statusColors = [
            'pending' => 'FFC000',
            'checked' => '00B0F0',
            'approved' => '92D050',
            'rejected' => 'FF0000',
            'completed' => '92D050',
        ];
        
        if (isset($statusColors[strtolower($request->status)])) {
            $statusColor = $statusColors[strtolower($request->status)];
            $sheet->getStyle('F11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($statusColor);
            $sheet->getStyle('F11')->getFont()->setBold(true);
            $sheet->getStyle('F11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
    
    $sheet->setCellValue('B12', 'Request Name:');
    $sheet->setCellValue('C12', $request->request_name);
    $sheet->getStyle('B12')->getFont()->setBold(true);
    
    $sheet->setCellValue('E12', 'Priority:');
    $sheet->setCellValue('F12', ucfirst($request->priority));
    $sheet->getStyle('E12')->getFont()->setBold(true);
    
    // Color for priority
    if (!empty($request->priority)) {
        $priorityColors = [
            'low' => '92D050',
            'medium' => 'FFC000',
            'high' => 'FF0000',
        ];
        
        if (isset($priorityColors[strtolower($request->priority)])) {
            $priorityColor = $priorityColors[strtolower($request->priority)];
            $sheet->getStyle('F12')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($priorityColor);
            $sheet->getStyle('F12')->getFont()->setBold(true);
            $sheet->getStyle('F12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
    
    $sheet->setCellValue('B13', 'Request Date:');
    $sheet->setCellValue('C13', $request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d M Y') : 'N/A');
    $sheet->getStyle('B13')->getFont()->setBold(true);
    
    $sheet->setCellValue('B14', 'Department:');
    $sheet->setCellValue('C14', $request->department ?? 'N/A');
    $sheet->getStyle('B14')->getFont()->setBold(true);
    
    $sheet->setCellValue('B15', 'Requested By:');
    $sheet->setCellValue('C15', $request->created_by ?? 'N/A');
    $sheet->getStyle('B15')->getFont()->setBold(true);
    
    // Space after info section
    $sheet->getRowDimension(16)->setRowHeight(10);
    
    // ===== TABLE HEADER =====
    $sheet->setCellValue('A17', 'No');
    $sheet->setCellValue('B17', 'Material Name');
    $sheet->setCellValue('C17', 'Quantity');
    $sheet->setCellValue('D17', 'Unit');
    $sheet->setCellValue('E17', 'Notes');
    
    // Style the table header
    $sheet->getStyle('A17:E17')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0062B6');
    $sheet->getStyle('A17:E17')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
    $sheet->getStyle('A17:E17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A17:E17')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getRowDimension(17)->setRowHeight(22);
    
    // ===== DATA ROWS =====
    $row = 18;
    $rowCount = count($request->items);
    
    foreach ($request->items as $index => $item) {
        // Set row height
        $sheet->getRowDimension($row)->setRowHeight(20);
        
        // Add zebra striping
        if ($index % 2 == 0) {
            $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('EEF4FF');
        }
        
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $item->product);
        $sheet->setCellValue('C' . $row, $item->quantity ?? '-');
        $sheet->setCellValue('D' . $row, $item->unit ?? 'pcs');
        $sheet->setCellValue('E' . $row, $item->notes ?? '-');
        
        $sheet->getStyle('A' . $row . ':E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A' . $row . ':E' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C' . $row . ':D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B' . $row . ':E' . $row)->getAlignment()->setWrapText(true);
        
        $row++;
    }
    
    // Add totals row
    $sheet->setCellValue('D' . $row, 'Total Items:');
    $sheet->setCellValue('E' . $row, $rowCount);
    $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->setBold(true);
    $sheet->getStyle('D' . $row . ':E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('D' . $row . ':E' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E7EFFA');
    $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $row += 2;
    
    // ===== NOTES SECTION =====
    if (!empty($request->notes)) {
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->setCellValue('A' . $row, 'NOTES:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E7EFFA');
        $sheet->getStyle('A' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        $row++;
        
        $sheet->mergeCells('A' . $row . ':F' . ($row + 2));
        $sheet->setCellValue('A' . $row, $request->notes);
        $sheet->getStyle('A' . $row . ':F' . ($row + 2))->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle('A' . $row . ':F' . ($row + 2))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getRowDimension($row)->setRowHeight(50);
        
        $row += 4;
    } else {
        $row += 1;
    }
    
    // ===== APPROVAL SECTION =====
    $sheet->mergeCells('A' . $row . ':F' . $row);
    $sheet->setCellValue('A' . $row, 'APPROVAL SECTION');
    $sheet->getStyle('A' . $row)->getFont()->setBold(true);
    $sheet->getStyle('A' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E7EFFA');
    $sheet->getStyle('A' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $row++;
    $sheet->getRowDimension($row)->setRowHeight(5); // Add spacing
    $row++;
    
    // Create approval boxes with better layout
    $sheet->mergeCells('B' . $row . ':C' . $row);
    $sheet->setCellValue('B' . $row, 'REVIEWED BY');
    $sheet->getStyle('B' . $row)->getFont()->setBold(true);
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B' . $row . ':C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E7EFFA');
    $sheet->getStyle('B' . $row . ':C' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    $sheet->mergeCells('E' . $row . ':F' . $row);
    $sheet->setCellValue('E' . $row, 'APPROVED BY');
    $sheet->getStyle('E' . $row)->getFont()->setBold(true);
    $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E' . $row . ':F' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E7EFFA');
    $sheet->getStyle('E' . $row . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    // Status boxes
    $sheet->mergeCells('B' . ($row + 1) . ':C' . ($row + 5));
    $checkerStatus = '';
    $checkerColor = 'FFFFFF';
    
    if ($request->checker) {
        if ($request->status == 'rejected' && isset($request->rejected_by) && $request->rejected_by == 'checker') {
            $checkerStatus = 'REJECTED';
            $checkerColor = 'FFCCCC';
        } else {
            $checkerStatus = 'REVIEWED';
            $checkerColor = 'E2EFDA';
        }
    }
    
    $sheet->setCellValue('B' . ($row + 1), $checkerStatus);
    $sheet->getStyle('B' . ($row + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('B' . ($row + 1) . ':C' . ($row + 5))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('B' . ($row + 1) . ':C' . ($row + 5))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($checkerColor);
    
    $sheet->mergeCells('E' . ($row + 1) . ':F' . ($row + 5));
    $approverStatus = '';
    $approverColor = 'FFFFFF';
    
    if ($request->approved) {
        if ($request->status == 'approved') {
            $approverStatus = 'APPROVED';
            $approverColor = 'E2EFDA';
        } else if ($request->status == 'rejected' && isset($request->rejected_by) && $request->rejected_by == 'approver') {
            $approverStatus = 'REJECTED';
            $approverColor = 'FFCCCC';
        }
    }
    
    $sheet->setCellValue('E' . ($row + 1), $approverStatus);
    $sheet->getStyle('E' . ($row + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('E' . ($row + 1) . ':F' . ($row + 5))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('E' . ($row + 1) . ':F' . ($row + 5))->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($approverColor);
    
    // Names & dates with better spacing
    $row += 6;
    $sheet->mergeCells('B' . $row . ':C' . $row);
    $sheet->setCellValue('B' . $row, $request->checker ?? '');
    $sheet->getStyle('B' . $row)->getFont()->setBold(true);
    $sheet->getStyle('B' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $sheet->mergeCells('E' . $row . ':F' . $row);
    $sheet->setCellValue('E' . $row, $request->approved ?? '');
    $sheet->getStyle('E' . $row)->getFont()->setBold(true);
    $sheet->getStyle('E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $row++;
    $sheet->mergeCells('B' . $row . ':C' . $row);
    $sheet->setCellValue('B' . $row, $request->checker_at ? \Carbon\Carbon::parse($request->checker_at)->format('d M Y') : '');
    $sheet->getStyle('B' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $sheet->mergeCells('E' . $row . ':F' . $row);
    $sheet->setCellValue('E' . $row, $request->approved_at ? \Carbon\Carbon::parse($request->approved_at)->format('d M Y') : '');
    $sheet->getStyle('E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // ===== FOOTER SECTION =====
    $row += 3;
    $sheet->mergeCells('A' . $row . ':F' . $row);
    $sheet->setCellValue('A' . $row, 'Generated on: ' . date('d M Y H:i:s'));
    $sheet->getStyle('A' . $row)->getFont()->setItalic(true)->setSize(9);
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    
    // Output file
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Material_Request_' . str_pad($request->id, 4, '0', STR_PAD_LEFT) . '.xlsx';
    
    // Download response
    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $fileName, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}
    public function history()
    {
        $query = MaterialRequest::with('items')
            ->whereIn('status', ['approved', 'rejected'])
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
    
        $history = $query->get();
    
        // Export logic jika dibutuhkan
        if (request()->has('format')) {
            return $this->exportData(request('format'), $history);
        }

        $project= Project::all();
    
        return view('user.material_history', compact('history', 'startDate', 'endDate','project'));
    }
    
}