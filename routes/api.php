<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;

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

Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/role/create', [RoleController::class, 'create']);
    Route::post('/transaction/cancel', [TransactionController::class, 'cancel']);
    Route::post('/transaction/deposit', [TransactionController::class, 'deposit']);
    Route::post('/transfer', [TransactionController::class, 'transfer']);
});
