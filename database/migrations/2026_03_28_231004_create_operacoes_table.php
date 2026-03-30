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

        $table->decimal('valor_requerido', 15, 2);
        $table->decimal('valor_desembolso', 15, 2);
        $table->decimal('total_juros', 15, 2);
        $table->decimal('taxa_juros', 10, 5);
        $table->decimal('taxa_multa', 10, 5);
        $table->decimal('taxa_mora', 10, 5);

        $table->string('status')->index();
        $table->string('produto');
        $table->integer('conveniada_id')->index();
        $table->string('conveniada_nome');

        $table->string('cpf')->index();
        $table->string('nome');
        $table->string('email')->nullable();

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
