<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ItemController;

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

            Route::get('category', [CategoryController::class, 'index']);
            Route::post('category', [CategoryController::class, 'store']);
            Route::get('category/{category}', [CategoryController::class, 'show']);
            Route::put('category/{category}', [CategoryController::class, 'update']);
            Route::delete('category/{category}', [CategoryController::class, 'destroy']);


            Route::post('item', [ItemController::class, 'store']);
            Route::get('item', [ItemController::class, 'index']);
            Route::get('item/{item}', [ItemController::class, 'show']);
            Route::put('item/{item}', [ItemController::class, 'update']);
            Route::delete('item/{item}', [ItemController::class, 'destroy']);

            // Route::post('logout', 'AuthController@logout');
            //     Route::get('profile', 'PageController@profile');

            //     Route::get('transaction', 'PageController@transaction');
            //     Route::get('transaction/{trx_id}', 'PageController@transactionDetail');

            //     Route::get('notification', 'PageController@notification');
            //     Route::get('notification/{id}', 'PageController@notificationDetail');

            //     Route::get('to-account-verify', 'PageController@toAccountVerify');
            //     Route::get('transfer/confirm', 'PageController@transferConfirm');
            //     Route::post('transfer/complete', 'PageController@transferComplete');

            //     Route::get('scan-and-pay-form', 'PageController@scanAndPayForm');
            //     Route::get('/scan-and-pay/confirm', 'PageController@scanAndPayConfirm');
            //     Route::post('/scan-and-pay/complete', 'PageController@scanAndPayComplete');
        });
    });
});
