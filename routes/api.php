<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Raffle\RaffleCreation;
use App\Http\Controllers\Ticket\TicketController;
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

    Route::prefix('ticket')->group(function (): void {
        Route::post('/create', [TicketController::class, 'store'])
            ->name('ticket.create');

        Route::get('/', [TicketController::class, 'index'])
            ->name('ticket.index');

        Route::get('/{id}', [TicketController::class, 'show'])
            ->name('ticket.show'); // Não protegido

        Route::put('/{id}', [TicketController::class, 'update'])
            ->name('ticket.update');

        Route::delete('/{id}', [TicketController::class, 'destroy'])
            ->name('ticket.destroy');
    });

    // Grupo de rotas da rifa
    Route::prefix('raffle')->group(function (): void {
        Route::post('/create', [RaffleCreation::class, 'RaffleCreate'])
            ->name('raffle.create');

        Route::get('/', [RaffleCreation::class, 'index'])
            ->name('raffle.index');
        //eu errei pois peguei apenas pelo id mais o certo é verificar se o "id" (tem que ver se realmente é o melhor nome ) é a url cadastrada isso para ficar dinamico para o usuario e gerar um controle melhor de acessar no futuro
        Route::get('/{id}', [RaffleCreation::class, 'show'])
            ->name('raffle.show'); // Não protegido

        Route::put('/{id}', [RaffleCreation::class, 'update'])
            ->name('raffle.update');

        Route::delete('/{id}', [RaffleCreation::class, 'destroy'])
            ->name('raffle.destroy');

        Route::put('/{id}/toggle', [RaffleCreation::class, 'toggleIsEnable'])
            ->name('raffle.Enable');
    });
});
