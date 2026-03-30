<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();

            // Relacionamento - Indexado para performance em alto volume
            $table->foreignId('operacao_id')->index()->constrained('operacoes')->onDelete('cascade');

            // Dados da Parcela
            $table->integer('numero_parcela');
            $table->date('data_vencimento')->index();
            $table->date('data_pagamento')->nullable();
            $table->decimal('valor_parcela', 15, 2);

            // Cálculos Financeiros (Requisito: Valor Presente)
            $table->decimal('valor_presente', 15, 2)->nullable();

            // Controle de Status (Pago / Pendente)
            $table->string('status')->default('PENDENTE')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
