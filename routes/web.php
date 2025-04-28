<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ReservaCalController;
use App\Http\Controllers\RegistroReservaController;
use App\Http\Controllers\UsuarioController;


Route::middleware('auth')->group(function(){
    Route::get('/', [MainController::class, 'index'])->name('home');
    route::get('calendario', [ReservaCalController::class, 'index'])->name('calendario');
    Route::resource('verRegistro', RegistroReservaController::class);
    Route::resource('reservaCal', ReservaCalController::class);
});


Route::resource('/usuario', UsuarioController::class);


route::get('/login', [UsuarioController::class, 'loginfrm'])->name('login');
route::post('/login', [UsuarioController::class, 'login'])->name('loginpost');
route::get('logout', [UsuarioController::class, 'logout'])->name('logout');

