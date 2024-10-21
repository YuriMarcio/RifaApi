<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Raffle\RaffleCreation;
use Illuminate\Support\Facades\Route;

// Redireciona a rota principal para a documentação do Swagger


// Grupo de rotas de autenticação
Route::group(['middleware' => ['api']], function () {
    Route::prefix('auth')->group(function (): void {
        Route::post('/registeremployeer', [AuthController::class, 'createEmployer']);
        Route::post('/teste', [AuthController::class, 'teste']);
        Route::post('/loginuser', [AuthController::class, 'loginUser']);
        Route::post('/loginemployer', [AuthController::class, 'loginEmployer']);
        Route::post('/register', [AuthController::class, 'createUser']);
    });

    // Grupo de rotas da rifa
    Route::prefix('raffle')->group(function (): void {
        Route::post('/create', [RaffleCreation::class, 'RaffleCreate'])
            ->name('raffle.create')
            ->middleware('auth'); // Protegido

        Route::get('/', [RaffleCreation::class, 'index'])
            ->name('raffle.index')
            ->middleware('auth'); // Protegido

        Route::get('/{id}', [RaffleCreation::class, 'show'])
            ->name('raffle.show'); // Não protegido

        Route::put('/{id}', [RaffleCreation::class, 'update'])
            ->name('raffle.update')
            ->middleware('auth'); // Protegido

        Route::delete('/{id}', [RaffleCreation::class, 'destroy'])
            ->name('raffle.destroy')
            ->middleware('auth'); // Protegido
    });
});
