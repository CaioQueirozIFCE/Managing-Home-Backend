<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;
/* rotas de autenticação */
Route::prefix('/auth')->group(function(){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register-user', [AuthController::class, 'createAccount']);
    Route::get('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
});
/* rotas de uso */
Route::group([
    'middleware' => ['apiJwt']
], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/list-wallet', [WalletController::class, 'showListWallet']);
    Route::post('/store', [WalletController::class, 'store']);
    Route::post('/update', [WalletController::class, 'update']);
});
