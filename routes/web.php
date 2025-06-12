<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ReservaCalController;
use App\Http\Controllers\RegistroReservaController;
use App\Http\Controllers\UsuarioController;


Route::middleware('auth')->group(function(){
    // Route::get('/', [MainController::class, 'index'])->name('calendario');
    route::get('/', [ReservaCalController::class, 'index'])->name('calendario');
    route::get('/dia', [ReservaCalController::class, 'index'])->name('calendario'); // Ruta para vista diaria (mismo método index)
    Route::resource('verRegistro', RegistroReservaController::class);
    Route::resource('reservaCal', ReservaCalController::class);
    route::get('tabla2', [RegistroReservaController::class, 'index2'])->name('tabla2');
    Route::post('reservaCal/{reservaCal}/cancel', [ReservaCalController::class, 'cancel'])->name('reservaCal.cancel');
    Route::post("/reservaCal/{reservaCal}/restaurar", [ReservaCalController::class, 'restaurar'])->name('reservaCal.restaurar');

});


Route::resource('/usuario', UsuarioController::class);


route::get('/login', [UsuarioController::class, 'loginfrm'])->name('login');
route::post('/login', [UsuarioController::class, 'login'])->name('loginpost');
route::get('logout', [UsuarioController::class, 'logout'])->name('logout');
