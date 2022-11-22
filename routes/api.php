<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\DamageItemController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\StockController;
use App\Http\Controllers\Api\V1\OutStockController;
use App\Http\Controllers\ReportItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::namespace('Api\V1')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);

            // Categories Routing
            Route::get('category', [CategoryController::class, 'index']);
            Route::post('category', [CategoryController::class, 'store']);
            Route::get('category/{category}', [CategoryController::class, 'show']);
            Route::put('category/{category}', [CategoryController::class, 'update']);
            Route::delete('category/{category}', [CategoryController::class, 'destroy']);

            // Items Routing
            Route::post('item', [ItemController::class, 'store']);
            Route::get('item', [ItemController::class, 'index']);
            Route::get('item/{item}', [ItemController::class, 'show']);
            Route::put('item/{item}', [ItemController::class, 'update']);
            Route::delete('item/{item}', [ItemController::class, 'destroy']);
            // Route::put('item-quantity/{item}', [ItemController::class, 'updateQuantity']);

            // InStock Routing
            Route::post('stock', [StockController::class, 'store']);
            Route::get('stock', [StockController::class, 'index']);
            Route::get('stock/{stock}', [StockController::class, 'show']);
            Route::put('stock/{stock}', [StockController::class, 'update']);
            Route::delete('stock/{stock}', [StockController::class, 'destroy']);

            // OutStock Routing
            Route::post('out-stock', [OutStockController::class, 'store']);
            Route::get('out-stock', [OutStockController::class, 'index']);
            Route::get('out-stock/{stock}', [OutStockController::class, 'show']);
            Route::put('out-stock/{stock}', [OutStockController::class, 'update']);
            Route::delete('out-stock/{stock}', [OutStockController::class, 'destroy']);

            // DamageItems Routing
            Route::post('damage-item', [DamageItemController::class, 'store']);
            Route::get('damage-item', [DamageItemController::class, 'index']);
            Route::get('damage-item/{stock}', [DamageItemController::class, 'show']);
            Route::put('damage-item/{stock}', [DamageItemController::class, 'update']);
            Route::delete('damage-item/{stock}', [DamageItemController::class, 'destroy']);

            //Report Item
            Route::get('item-report', [ReportItemController::class, 'index']);

        });
    });
});