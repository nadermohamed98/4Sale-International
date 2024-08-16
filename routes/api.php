<?php

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

Route::group(['prefix' => 'tables'],function(){
    Route::post('/check_avilability',[TableController::class,'checkAvilability']);
    Route::post('/reserve_table',[TableController::class,'reserveTable']);
});

Route::group(['prefix' => 'meals'],function(){
    Route::get('/all',[MealController::class,'index']);
});

Route::group(['prefix' => 'orders'],function(){
    Route::post('/create',[OrderController::class,'store']);
});

Route::get('all',function(){
    return "hello";
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
