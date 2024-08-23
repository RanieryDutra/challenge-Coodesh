<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', [ProductController::class, 'index']);                       //ok
Route::put('/products/{code}', [ProductController::class, 'update']);       //ok
Route::delete('/products/{code}', [ProductController::class, 'delete']);    //ok
Route::get('/products/{code}', [ProductController::class, 'show']);         //ok
Route::get('/products', [ProductController::class, 'list']);                //ok