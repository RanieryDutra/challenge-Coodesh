<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function ApiDetails(): JsonResponse
    {
        $uptime = shell_exec('uptime -p');
        $memoryUsage = memory_get_usage(true);
        $lastCronExecution = Product::latest('executed_at')->first();

        return response()->json([
            'status' => 'OK',
            'uptime' => trim($uptime),
            'memory_usage' => $this->formatBytes($memoryUsage),
            'last_cron_execution' => $lastCronExecution ? $lastCronExecution->executed_at : 'N/A',
        ]);
    }

    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    
    }
}