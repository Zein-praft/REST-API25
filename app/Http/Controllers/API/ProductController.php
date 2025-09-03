<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;
// use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::latest()->paginate(10);
        return response()->json(new ProductCollection($products), Response::HTTP_OK);
    }

    public function store(ProductRequest $request){
        $validated = $request->validated();
        $product = Product::create($validated);
        return response()->json([
            'status'    => true,
            'message'   => 'Product berhasil ditambahkan',
            'data'      => new ProductResource($product),
        ], Response::HTTP_CREATED);
    }

    public function show($id){
        $product = Product::findOrFail($id);

        return response()->json([
            'status'    => true,
            'message'   => 'Detail Product',
            'data'      => new ProductResource($product),
        ], Response::HTTP_OK);
    }

    public function update(ProductRequest $request, $id){

        $product = Product::findOrFail($id);

        $validated = $request->validated();
        $product->update($validated);

        return response()->json([
            'status'    => true,
            'message'   => 'Product berhasil di update',
            'data'      => new ProductResource($product),
        ], Response::HTTP_OK);
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Product berhasil di hapus',
        ], Response::HTTP_OK);
    }
}