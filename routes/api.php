<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\HomeController; // Corrigido para "App"
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function () {
    Route::prefix('auth')->group(function () { // Mudou 'Auth' para 'auth'
        Route::post('/registeremployeer', [AuthController::class, 'createEmployer']);
        Route::post('/teste', [AuthController::class, 'teste']);
        Route::post('/loginuser', [AuthController::class, 'loginUser']);
        Route::post('/loginemployer', [AuthController::class, 'loginEmployer']);
        Route::post('/register', [AuthController::class, 'createUser']);
    });
});

// Rotas autenticadas (auth true)
Route::middleware('jwt.check')->group(function () {
    Route::prefix('person')->group(function () {
        Route::get('/', [AuthController::class, 'show']);
    });
    Route::prefix('raffle')->group(function () {
        Route::get('/', [AuthController::class, 'show']);
    });
    Route::prefix('users')->group(function () { // Mudou 'Users' para 'users'
        Route::get('/', [AuthController::class, 'show']);
    });
});
