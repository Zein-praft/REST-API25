<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Response;
use App\Http\Controllers\API;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index (){
        $product = Product::latest()->paginate(10);
        return response()->json(new ProductCollection($product), Response::HTTP_OK);
    }
}