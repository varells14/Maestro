<?php

// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        
        Category::create($validated);
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }
    
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
        
        $category->update($validated);
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }
    
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih terkait dengan data lain');
        }
    }
}
