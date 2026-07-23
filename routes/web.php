<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\AuthController;
use App\Http\Controllers\POS\ReceiptController;
use App\Http\Controllers\POS\SalesHistoryController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| POS Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/pos/login', [AuthController::class, 'showLogin'])
        ->name('pos.login');

    Route::post('/pos/login', [AuthController::class, 'login'])
        ->name('pos.login.submit');
});



/*
|--------------------------------------------------------------------------
| Protected POS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Cashier|Manager|Admin'])->group(function () {

    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos');

    Route::post('/pos/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('pos.logout');

});

Route::middleware(['auth', 'role:Cashier|Manager|Admin'])->group(function () {

    Route::get('/pos/history', [SalesHistoryController::class, 'index'])
        ->name('pos.history');
    
    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos');

    Route::get('/pos/receipt/{sale}', [ReceiptController::class, 'show'])
        ->name('pos.receipt');

    Route::post('/pos/logout', [AuthController::class, 'logout'])
        ->name('pos.logout');
});