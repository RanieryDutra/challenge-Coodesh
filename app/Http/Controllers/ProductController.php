<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'API is working',
            'last_cron_execution' => Carbon::now()->toDateTimeString(),
            'memory_usage' => memory_get_usage(),
        ]);
    }

    public function update(Request $request, $code)
    {
        $product = Product::where('code', $code)->update($request->all());
        $productAtt = Product::where('code', $code)->firstOrFail();
        return response()->json($productAtt, 200);
    }

    public function delete($code)
    {
        $product = Product::where('code', $code)->update(['status' => 'trash']);
        return response()->json(['status' => 'trash'], 200);
    }

    public function show($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        return response()->json($product);
    }

    public function list(Request $request)
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }

}