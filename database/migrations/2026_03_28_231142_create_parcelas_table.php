<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::create('parcelas', function (Blueprint $table) {
        $table->id();


        $table->foreignId('operacao_id')
            ->index() // buscar rapido por operação
            ->constrained('operacoes')
            ->onDelete('cascade');

        $table->integer('numero_parcela');
        $table->date('data_vencimento')->index(); //  filtros de "Vencidos"
        $table->date('data_pagamento')->nullable(); // Para controle de baixa

        $table->decimal('valor_parcela', 15, 2);

        //  guardar o valor presente calculado
        $table->decimal('valor_presente', 15, 2)->nullable();

        $table->string('status')->default('PENDENTE')->index();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
