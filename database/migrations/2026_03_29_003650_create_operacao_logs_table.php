<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operacao_logs', function (Blueprint $table) {
            $table->id();

            // Relacionamento com Operação
            $table->foreignId('operacao_id')->constrained('operacoes')->onDelete('cascade');

            // Dados do Log
            $table->string('status_anterior')->nullable();
            $table->string('status_novo');

            // Observação opcional para registrar motivo da mudança de status
            $table->text('observacao')->nullable();

            // Controle de Auditoria
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operacao_logs');
    }
};
