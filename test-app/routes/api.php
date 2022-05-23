<?php

use \App\Http\Controllers\Api\WalletController;
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

//Группа роутов по токен-авторизации

Route::middleware('auth:api_token')->group(function() {

    Route::get('get-balance', [WalletController::class, 'show']);
    Route::get('edit-balance', [WalletController::class, 'edit']); //Сделать пост метод

});
