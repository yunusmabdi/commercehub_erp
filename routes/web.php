<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:Cashier'])->group(function () {

    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos');

});


Route::get('/pos/login', [AuthController::class, 'showLogin'])
    ->name('pos.login');

Route::post('/pos/login', [AuthController::class, 'login'])
    ->name('pos.login.submit');

Route::post('/pos/logout', [AuthController::class, 'logout'])
    ->name('pos.logout');

Route::post('/pos/logout', [AuthController::class, 'logout'])
    ->name('pos.logout');

Route::get('/pos', function () {
    return view('pos.index');
})
->middleware('auth')
->name('pos');