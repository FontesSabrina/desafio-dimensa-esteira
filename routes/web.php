<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OperacaoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // --- GRUPO DE OPERAÇÕES ---
    Route::prefix('operacoes')->name('operacoes.')->group(function () {

        // Listagem
        Route::get('/dashboard', [OperacaoController::class, 'index'])->name('index');

        // Exportação e Importação
        Route::get('/exportar', [OperacaoController::class, 'exportar'])->name('exportar');
        Route::post('/importar', [OperacaoController::class, 'importar'])->name('importar');

        // Detalhes da Operação
        Route::get('/{id}', [OperacaoController::class, 'show'])->name('show');

        // --- AJUSTE AQUI: Mude de POST para PATCH ---
        // Isso combina com o @method('PATCH') que colocamos no formulário da view
        Route::patch('/{id}/status', [OperacaoController::class, 'atualizarStatus'])->name('atualizarStatus');
    });

    // Rota de redirecionamento do Dashboard padrão do Breeze
    Route::get('/dashboard', function () {
        return redirect()->route('operacoes.index');
    })->name('dashboard');

    // --- ROTAS DE PERFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
