<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
// use App\Services\ProductService;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    // ✅ Show Product Page
    public function index(Request $request)
    {
        return Inertia::render('Products', [
            'products' => $this->service->getAll($request)
        ]);
    }

    // ✅ Store Product
    public function store(ProductRequest $request)
    {
        $this->service->create($request->validated());

        return back()->with('success', 'Product created');
    }

    // ✅ Update Product
    public function update(ProductRequest $request, $id)
    {
        $this->service->update($id, $request->validated());

        return back()->with('success', 'Product updated');
    }

    // ✅ Toggle Stock
    public function toggleStock(Request $request, $id)
    {
        $this->service->toggleStock($id, $request->in_stock);

        return back()->with('success', 'Stock updated');
    }

    // ✅ Delete Product
    public function destroy($id)
    {
        $this->service->delete($id);

        return back()->with('success', 'Product deleted');
    }
}