<?php

// app/Http/Controllers/SupplierController.php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
        ]);
        
        Supplier::create($validated);
        
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan');
    }
    
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
        ]);
        
        $supplier->update($validated);
        
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui');
    }
    
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier tidak dapat dihapus karena masih terkait dengan data lain');
        }
    }
}