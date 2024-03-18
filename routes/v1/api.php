<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrivateChatController;
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



Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:api')->group(function () {
    Route::post('send-message', [PrivateChatController::class, 'sendMessage']);
    Route::get('logout', [AuthController::class, 'logout']);
});

