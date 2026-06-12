<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\PagamentoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/despesas', [DespesaController::class, 'index'])->name('despesas.index');
Route::get('/despesas/{despesa}', [DespesaController::class, 'show'])->name('despesas.show')->whereNumber('despesa');

Route::get('/pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index');
Route::get('/pagamentos/{pagamento}', [PagamentoController::class, 'show'])->name('pagamentos.show')->whereNumber('pagamento');

Route::middleware('guest')->group(function () {
    Route::get('/entrar', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/entrar', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/cadastro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/cadastro', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/sair', [AuthController::class, 'logout'])->name('logout');

    Route::resource('despesas', DespesaController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('pagamentos', PagamentoController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});
