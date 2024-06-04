<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductApiController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $allProducts = $this->productService->getAllProducts();
        $storageUrl = asset('storage');

        return response()->json([
            'success' => true,
            'data' => ['all_products' => $allProducts, 'storage_url' => $storageUrl]
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'directions' => 'required|string|max:65535',
            'price' => 'required|numeric|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'in_stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = $this->productService->createProduct($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product,
        ]);
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);

            return response()->json([
                'success' => true,
                'data' => ['product' => $product, 'storage_url' => asset('storage')]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'directions' => 'required|string|max:65535',
            'price' => 'required|numeric|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'in_stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|string|max:2048'
        ]);

        try {
            $product = $this->productService->updateProduct($id, $validatedData);

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product,
                'baseUrl' => asset('storage')
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        }
    }
}
