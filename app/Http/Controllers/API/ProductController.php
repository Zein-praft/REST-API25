<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
// use GuzzleHttp\Psr7\Response;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::latest()->paginate(10);
        return response()->json(new ProductCollection($product), Response::HTTP_OK);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Product berhasil di tambahkan',
            'data' => new ProductResource($product),
        ], Response::HTTP_CREATED);
    }
    public function show($id) {
        $product = Product::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Detail Product',
            'data' => new ProductResource($product),
        ], Response::HTTP_OK);
    }
    public function update(ProductRequest $request, $id) {
        $product = Product::findOrFail($id);

        $validated = $request->validated();
        $product->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Product berhasil di update',
            'data' => new ProductResource($product),
        ], Response::HTTP_OK);
    }
    public function destroy(Product $product) {
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product berhasil di hapus',
        ], Response::HTTP_OK);
    }
}