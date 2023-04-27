<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExchangeController;
use App\Http\Controllers\Api\TransactionController;
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

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(TransactionController::class)
        ->prefix('transaction')->group(function () {
            Route::get('/all', 'index');
            Route::get('/{transaction}', 'show')->middleware('update.last.seen');
            Route::post('/store', 'store');
            Route::put('/update/{transaction}', 'update');
            Route::delete('/delete/{transaction}', 'destroy');

        });
    Route::controller(ExchangeController::class)->group(function () {
        Route::get('/exchange', 'exchange');
    });
    Route::get('/sum',[TransactionController::class, 'authUserTransactionsSum']);
});
