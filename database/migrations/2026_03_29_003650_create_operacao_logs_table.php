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

            $table->foreignId('operacao_id')->constrained('operacoes')->onDelete('cascade');

            $table->string('status_anterior')->nullable();
            $table->string('status_novo');

            $table->text('observacao')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operacao_logs');
    }
};
