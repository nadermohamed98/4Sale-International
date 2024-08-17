<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

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
// Route::get('/test-api', function() {
//     return response()->json(['message' => 'API route works!']);
// });

Route::group(['prefix' => 'user'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group(['prefix' => 'tables'], function () {
    Route::post('/check_avilability', [TableController::class, 'checkAvilability']);
    Route::post('/reserve_table', [TableController::class, 'reserveTable']);
});

Route::group(['prefix' => 'meals'], function () {
    Route::get('/all', [MealController::class, 'index']);
});

Route::group(['prefix' => 'orders'], function () {
    Route::post('/create', [OrderController::class, 'store']);
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'orders'], function () {
        Route::post('/checkout', [OrderController::class, 'checkout']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
