<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
        $this->middleware('permission:view_product')->only(['index']);
        $this->middleware('permission:create_product')->only(['store']);
        $this->middleware('permission:edit_product')->only(['update']);
        $this->middleware('permission:delete_product')->only(['destroy']);
    }

    public function index(Request $request)
{
    return response()->json(
        $this->service->getAll($request)
    );
}

    public function store(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Created',
            'data' => $product
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = $this->service->update($id, $request->validated());

        return response()->json([
            'message' => 'Updated',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Deleted'
        ]);
    }

    public function toggleStock(Request $request, $id)
{
    $product = $this->service->toggleStock($id, $request->in_stock);

    return response()->json([
        'success' => true,
        'data' => $product
    ]);
}
}