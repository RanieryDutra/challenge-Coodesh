<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\CronLog;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        try {
            try {
                DB::connection()->getPdo();
                $checkDBReadConnection = 'OK';
            } catch (\Exception $e) {
                $checkDBReadConnection = 'Failed: ' . $e->getMessage();
            }
            // Verificar conexão de escrita com o banco de dados
            try {
                DB::connection()->getPdo()->exec('DO 1');
                $checkDBWriteConnection = 'OK';
            } catch (\Exception $e) {
                $checkDBWriteConnection = 'Failed: ' . $e->getMessage();
            }
            $lastCron         = CronLog::query()->orderBy('id', 'desc')->first();
            $lastCronExecuted = $lastCron ? Carbon::parse($lastCron->executed_at)->timezone('America/Sao_Paulo')
                                                  ->format('d/m/y H:i') : 'No cron executed yet';
            $memoryUsage      = memory_get_usage();
            $uptime           = exec('uptime');

            return response()->json([
                'status_read_db_connection'  => $checkDBReadConnection,
                'status_write_db_connection' => $checkDBWriteConnection,
                'last_cron_execution'        => $lastCronExecuted,
                'memory_usage'               => $memoryUsage,
                'uptime'                     => $uptime,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'An error occurred while processing the request',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(ProductUpdateRequest $request, $code)
    {
        try {
            $data                    = $request->validated();
            $product                 = Product::query()->where('code', $code)->firstOrFail();
            $data['last_modified_t'] = Carbon::createFromTimestamp(now());
            $product->update($data);
            $product->refresh();
            return response()->json(ProductResource::make($product), 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Error updating product',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function delete($code)
    {
        try {
            $product = Product::query()->where('code', $code)->firstOrFail();

            $product->update(['status' => 'trash']);

            return response()->json(['status' => 'trash'], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'An error occurred when trying to move the product to the trash',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($code)
    {
        try {
            $product = Product::query()->where('code', $code)->firstOrFail();
            return response()->json(ProductResource::make($product), 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'An error occurred when trying to search for the product',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function list(Request $request)
    {
        try {
            $products = Product::query()->paginate(10);
            return response()->json(ProductResource::collection($products), 200);

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'An error occurred while listing products',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


}