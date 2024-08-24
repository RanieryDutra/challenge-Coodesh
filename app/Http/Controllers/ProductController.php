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
        try
        {
            $status = 'API is working';
            $lastCronExecution = Carbon::now('America/Sao_Paulo')->toDateTimeString();
            $memoryUsage = memory_get_usage();
    
            return response()->json([
                'status' => $status,
                'last_cron_execution' => $lastCronExecution,
                'memory_usage' => $memoryUsage,
            ], 200);
    
        } catch (\Exception $e)
        {
            return response()->json([
                'error' => 'An error occurred while processing the request',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function update(Request $request, $code)
    {
        try 
        {
            $product = Product::where('code', $code)->update($request->all());
            $productAtt = Product::where('code', $code)->firstOrFail();
            return response()->json($productAtt, 200);

        } catch (\Exception $e) 
        {
            return response()->json([
                'error' => 'Error updating product',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function delete($code)
    {
        try
        {
            $product = Product::where('code', $code)->update(['status' => 'trash']);
    
            if ($product) {
                return response()->json(['status' => 'trash'], 200);
            } else {
                return response()->json(['error' => 'Product not found or unable to delete'], 404);
            }
    
        } catch (\Exception $e)
        {
            return response()->json([
                'error' => 'An error occurred when trying to move the product to the trash',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function show($code)
    {
        try
        {
            $product = Product::where('code', $code)->firstOrFail();
            return response()->json($product, 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return response()->json([
                'error' => 'Product not found',
            ], 404);
    
        } catch (\Exception $e)
        {
            return response()->json([
                'error' => 'An error occurred when trying to search for the product',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function list(Request $request)
    {
        try 
        {
            $products = Product::paginate(10);
            return response()->json($products, 200);
    
        } catch (\Exception $e)
        {
            return response()->json([
                'error' => 'An error occurred while listing products',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

}