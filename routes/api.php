<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function () {
    Route::prefix('Auth')->group(function () {
        Route::post('/registeremployeer', [AuthController::class, 'createEmployer']);
        Route::post('/loginuser', [AuthController::class, 'loginUser']);
        Route::post('/loginemployer', [AuthController::class, 'loginEmployer']);
        Route::post('/register', [AuthController::class, 'createUser']);
    });
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::middleware('auth:sanctum')->prefix('person')->group(function () {
//         Route::get('/', [AuthController::class, 'show'])->middleware('auth:sanctum');
//     });
//     Route::middleware('auth:sanctum')->prefix('raffle')->group(function () {
//         Route::get('/', [AuthController::class, 'show'])->middleware('auth:sanctum');
//     });
// });

// Route::middleware('auth:sanctum')->prefix('Users')->group(function () {
//     Route::get('/', [AuthController::class, 'show'])->middleware('auth:sanctum');
// });
