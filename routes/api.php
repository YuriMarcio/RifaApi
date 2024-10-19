<?php
use App\Http\Controllers\AuthController;
use APP\Http\Controllers\api\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function () {
    Route::prefix('Auth')->group(function () {
        Route::post('/registeremployeer', [AuthController::class, 'createEmployer']);
        Route::post('/loginuser', [AuthController::class, 'loginUser']);
        Route::post('/loginemployer', [AuthController::class, 'loginEmployer']);
        Route::post('/register', [AuthController::class, 'createUser']);
    });
});


// rotas auth true

Route::middleware('jwt.check')->group(function () {
    Route::prefix('person')->group(function () {
        Route::get('/', [AuthController::class, 'show']);
    });
    Route::prefix('raffle')->group(function () {
        Route::get('/', [AuthController::class, 'show']);
    });
    Route::prefix('Users')->group(function () {
        Route::get('/', [AuthController::class, 'show']);
    });
});


Route::get('/', [HomeController::class, 'index']);

