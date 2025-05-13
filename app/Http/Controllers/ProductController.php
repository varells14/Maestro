<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
           
            'name' => 'required',
            
            'stock' => 'required|integer|min:0',
            'description' => 'nullable',
        ]);
        
        Product::create($validated);
        
        return redirect()->route('products.index')->with('success', 'Material success added');
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
           
            'name' => 'required',
            
            'stock' => 'required|integer|min:0',
            'description' => 'nullable',
        ]);
        
        $product->update($validated);
        
        return redirect()->route('products.index')->with('success', 'Material success updated');
    }
    
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Material success deleted');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Material cannot be deleted because it is in use');
        }
    }
}
