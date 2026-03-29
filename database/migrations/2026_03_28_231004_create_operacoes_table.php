<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::create('operacoes', function (Blueprint $table) {
        $table->id();
        $table->string('codigo_operacao')->unique();

        // Financeiro com precisão decimal
        $table->decimal('valor_requerido', 15, 2);
        $table->decimal('valor_desembolso', 15, 2);
        $table->decimal('total_juros', 15, 2);
        $table->decimal('taxa_juros', 10, 5);
        $table->decimal('taxa_multa', 10, 5);
        $table->decimal('taxa_mora', 10, 5);

        // Status e Identificação com Índices para performance
        $table->string('status')->index();
        $table->string('produto');
        $table->integer('conveniada_id')->index();
        $table->string('conveniada_nome');

        // Dados do Cliente
        $table->string('cpf')->index(); // Busca por CPF será rápida
        $table->string('nome');
        $table->string('email')->nullable();

        // Datas importantes
        $table->date('data_criacao');
        $table->date('data_pagamento')->nullable();

        $table->integer('quantidade_parcelas');
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('operacoes');
    }
};
