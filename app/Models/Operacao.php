<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operacao extends Model
{
    protected $table = 'operacoes';

    protected $guarded = [];

    const STATUS_DIGITANDO = 'DIGITANDO';
    const STATUS_PRE_ANALISE = 'PRÉ-ANÁLISE';
    const STATUS_EM_ANALISE = 'EM ANÁLISE';
    const STATUS_PARA_ASSINATURA = 'PARA ASSINATURA';
    const STATUS_ASSINATURA_CONCLUIDA = 'ASSINATURA CONCLUÍDA';
    const STATUS_APROVADA = 'APROVADA';
    const STATUS_CANCELADA = 'CANCELADA';
    const STATUS_PAGO_AO_CLIENTE = 'PAGO AO CLIENTE';

    protected $casts = [
        'data_pagamento' => 'datetime',
        'data_criacao' => 'datetime',
    ];

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }

    public function logs()
    {
        return $this->hasMany(OperacaoLog::class, 'operacao_id', 'id');
    }
}
