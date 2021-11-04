<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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
Route::get('/login', function (Request $request) {
    return response()->json(['message' => 'Login with email and password'], 401);
})->name('login');

Route::group([
    'middleware'=>'api',
    'prefix'=>'auth'
], function ($route){
    Route::post('/login', AuthController::class.'@login');

});

Route::group([
    'prefix'=>'user'
], function ($route){
    Route::get('/', UserController::class.'@index');
    Route::get('/{id}', UserController::class.'@show')->where('id', '[0-9]+');
    Route::post('/', UserController::class.'@store');
    Route::put('/{id}', UserController::class.'@update')->where('id', '[0-9]+');
    Route::delete('/{id}', UserController::class.'@destroy');
});

Route::group([
    'prefix'=>'refund'
], function ($route){
    Route::get('/', RefundController::class.'@index');
    Route::get('/{id}', RefundController::class.'@show')->where('id', '[0-9]+');
    Route::post('/', RefundController::class.'@store');
    Route::put('/{id}', RefundController::class.'@update')->where('id', '[0-9]+');
    Route::delete('/{id}', RefundController::class.'@destroy')->where('id', '[0-9]+');
});




