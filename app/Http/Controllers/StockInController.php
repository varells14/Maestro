<?php
// app/Http/Controllers/StockInController.php
namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\Product;
use App\Models\Supplier;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $query = StockIn::with(['product', 'supplier'])->orderBy('date', 'desc');
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
    
        $stockIns = $query->get();
        $products = Product::all();
        $suppliers = Supplier::all();
    
        return view('stock_ins.index', compact('stockIns', 'products', 'suppliers', 'request'));
    }

    public function exportExcel(Request $request)
{
    $query = StockIn::with(['product', 'supplier'])->orderBy('date', 'desc');

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    $stockIns = $query->get();

    // Buat spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Date');
    $sheet->setCellValue('C1', 'Product Name');
    $sheet->setCellValue('D1', 'Supplier');
    $sheet->setCellValue('E1', 'Quantity');
    $sheet->setCellValue('F1', 'Notes');

    
    // Data
    $row = 2;
    foreach ($stockIns as $index => $stockIn) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($stockIn->date)));
        $sheet->setCellValue('C' . $row, $stockIn->product->name ?? '');
        $sheet->setCellValue('D' . $row, $stockIn->supplier->name ?? '');
        $sheet->setCellValue('E' . $row, $stockIn->quantity);
        $sheet->setCellValue('F' . $row, $stockIn->notes);
        $row++;
    }

    // Simpan ke output
    $writer = new Xlsx($spreadsheet);
    $fileName = 'stock-in-report.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}
    
    
    public function store(Request $request)
    {
        $validated = $request->validate([
           
            'product_id' => 'required|exists:products,id',
           
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable',
        ]);
        
        DB::transaction(function() use ($validated) {
            // Create stock in record
            StockIn::create($validated);
            
            // Update product stock
            $product = Product::find($validated['product_id']);
            $product->stock += $validated['quantity'];
            $product->save();
        });
        
        return redirect()->route('stock-ins.index')->with('success', 'Material in successfully added');
    }


    public function update(Request $request, StockIn $stockIn)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        
        'quantity' => 'required|integer|min:1',
        'date' => 'required|date',
        'notes' => 'nullable',
    ]);

    DB::transaction(function () use ($validated, $stockIn) {
        // Sesuaikan stok: kurangi stok lama, tambah stok baru
        $oldProduct = Product::find($stockIn->product_id);
        $oldProduct->stock -= $stockIn->quantity;
        $oldProduct->save();

        $stockIn->update($validated);

        $newProduct = Product::find($validated['product_id']);
        $newProduct->stock += $validated['quantity'];
        $newProduct->save();
    });

    return redirect()->route('stock-ins.index')->with('success', 'Incoming stock successfully updated');
}
    
    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function() use ($stockIn) {
            // Update product stock
            $product = Product::find($stockIn->product_id);
            $product->stock -= $stockIn->quantity;
            $product->save();
            
            // Delete stock in record
            $stockIn->delete();
        });
        
        return redirect()->route('stock-ins.index')->with('success', 'Material in successfully deleted');
    }

    
}
