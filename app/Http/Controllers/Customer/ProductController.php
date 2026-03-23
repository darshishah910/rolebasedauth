<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    // ✅ Show Product Page
    public function index()
    {
        $products = Product::latest()->get();

        return Inertia::render('Products', [
            'products' => $products
        ]);
    }

    // ✅ Store Product
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|image',
        ]);
          $data['in_stock'] = $request->quantity > 0 ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return back()->with('success', 'Product created');
    }

    // ✅ Update Product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable',
            'image' => 'nullable|image',
            
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return back()->with('success', 'Product updated');
    }

    // ✅ Toggle Stock
    public function toggleStock(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'in_stock' => (bool) $request->in_stock
        ]);

        return back()->with('success', 'Stock updated');
    }

    // ✅ Delete Product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return back()->with('success', 'Product deleted');
    }
}