<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    // public function productUpdate(Request $request, $code): JsonResponse
    // {
    //     $product = Product::where('code', $code)->first();

    //     if (!$product) {
    //         return response()->json(['message' => 'Product not found'], 404);
    //     }

    //     $product->update($request->all());

    //     return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    // }

    // public function productDelete($code): JsonResponse
    // {
    //     $product = Product::where('code', $code)->first();

    //     if (!$product) {
    //         return response()->json(['message' => 'Product not found'], 404);
    //     }

    //     $product->status = 'trash';
    //     $product->save();

    //     return response()->json(['message' => 'Product marked as trash'], 200);
    // }

    // public function allProducts($code): JsonResponse
    // {
    //     $product = Product::where('code', $code)->first();

    //     if (!$product) {
    //         return response()->json(['message' => 'Product not found'], 404);
    //     }

    //     return response()->json(['product' => $product], 200);
    // }

    public function index()
    {
        return response()->json([
            'status' => 'API is working',
            'last_cron_execution' => Carbon::now()->toDateTimeString(), // substituir pelo real
            'memory_usage' => memory_get_usage(),
        ]);
    }

    public function update(Request $request, $code)
    {
        //$product = Product::where('code', $code)->firstOrFail();
        $product = Product::where('code', $code)->update($request->all());
        $productAtt = Product::where('code', $code)->firstOrFail();
        return response()->json($productAtt, 200);
    }

    public function delete($code)
    {
        $product = Product::where('code', $code)->update(['status' => 'trash']);
        // $product->status = 'trash';
        // $product::update('code', $code);
        return response()->json(['status' => 'trash'], 200);
    }

    public function show($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        return response()->json($product);
    }

    public function list(Request $request)
    {
        $products = Product::paginate(10); // Paginação para não sobrecarregar
        return response()->json($products);
    }

}