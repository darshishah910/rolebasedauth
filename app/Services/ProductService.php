<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAll($request)
    {
        $query = Product::query();

        // 🔍 Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📄 Pagination
        $products = $query->latest()->paginate(5);

        // Transform
        $products->getCollection()->transform(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'price' => $p->price,
                'quantity' => $p->quantity,
                'in_stock' => $p->in_stock,
                'image' => $p->image ? asset('storage/' . $p->image) : null,
            ];
        });

        return $products;
    }

    public function create($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        $data['in_stock'] = $data['quantity'] > 0 ? 1 : 0;

        return Product::create($data);
    }

    public function update($id, $data)
    {
        $product = Product::findOrFail($id);

        if (isset($data['image'])) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $data['image']->store('products', 'public');
        }

        $data['in_stock'] = $data['quantity'] > 0 ? 1 : 0;

        $product->update($data);

        return $product;
    }

    // ✅ ADD THIS (missing method)
    public function toggleStock($id, $status)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'in_stock' => (bool) $status
        ]);

        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
    }
}