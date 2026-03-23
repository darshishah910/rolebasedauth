<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAll()
    {
        return Product::latest()->get()->map(function ($p) {
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
    }

    public function create($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        $data['in_stock'] = isset($data['quantity'])
        ?($data['quantity'] > 0 ? 1 :0)
        : ($data['in_stock']??1);

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

    // ✅ auto update stock
    if (isset($data['quantity'])) {
        $data['in_stock'] = $data['quantity'] > 0;
    }

    $product->update($data);

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