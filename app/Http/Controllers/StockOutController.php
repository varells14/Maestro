<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk mendapatkan data StockOut dengan relasi produk
        $query = StockOut::with(['product'])->orderBy('date', 'desc');
    
        // Filter berdasarkan tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
    
        // Ambil data StockOut
        $stockOuts = $query->get();
    
        // Ambil data produk dengan stok lebih dari 0
        $products = Product::where('stock', '>', 0)->get();
    
        // Kembalikan view dengan data yang sudah diproses
        return view('stock_outs.index', compact('stockOuts', 'products', 'request'));
    }
    
    public function exportExcel(Request $request)
    {
        // Query untuk mendapatkan data StockOut dengan relasi produk
        $query = StockOut::with(['product'])->orderBy('date', 'desc');
    
        // Filter berdasarkan tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
    
        // Ambil data StockOut
        $stockOuts = $query->get();
    
        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Product Name');
        $sheet->setCellValue('D1', 'Quantity');
        $sheet->setCellValue('E1', 'Notes');
    
        // Data
        $row = 2;
        foreach ($stockOuts as $index => $stockOut) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($stockOut->date)));
            $sheet->setCellValue('C' . $row, $stockOut->product->name ?? '');
            $sheet->setCellValue('D' . $row, $stockOut->quantity);
            $sheet->setCellValue('E' . $row, $stockOut->notes);
            $row++;
        }
    
        // Simpan ke output
        $writer = new Xlsx($spreadsheet);
        $fileName = 'stock-out-report.xlsx';
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
            'destination' => 'nullable',
            'notes' => 'nullable',
        ]);
        
        $product = Product::find($validated['product_id']);
        
        if ($product->stock < $validated['quantity']) {
            return redirect()->back()->with('error', 'Stock cannot be less than the quantity to be sold');
        }
        
        DB::transaction(function() use ($validated, $product) {
            // Create stock out record
            StockOut::create($validated);
            
            // Update product stock
            $product->stock -= $validated['quantity'];
            $product->save();
        });
        
        return redirect()->route('stock-outs.index')->with('success', 'Material out successfully added');
    }
    
    public function destroy(StockOut $stockOut)
    {
        DB::transaction(function() use ($stockOut) {
            // Update product stock
            $product = Product::find($stockOut->product_id);
            $product->stock += $stockOut->quantity;
            $product->save();
            
            // Delete stock out record
            $stockOut->delete();
        });
        
        return redirect()->route('stock-outs.index')->with('success', 'Material out successfully deleted');
    }
}
