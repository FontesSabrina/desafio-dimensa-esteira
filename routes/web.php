<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OperacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {


    Route::prefix('operacoes')->name('operacoes.')->group(function () {
        Route::get('/dashboard', [OperacaoController::class, 'index'])->name('index');
        Route::get('/exportar', [OperacaoController::class, 'exportar'])->name('exportar');
        Route::post('/importar', [OperacaoController::class, 'importar'])->name('importar');
        Route::get('/{id}', [OperacaoController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [OperacaoController::class, 'atualizarStatus'])->name('atualizarStatus');
    });

    Route::get('/dashboard', function () {
        return redirect()->route('operacoes.index');
    })->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
