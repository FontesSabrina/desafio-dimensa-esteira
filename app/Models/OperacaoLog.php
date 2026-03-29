<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperacaoLog extends Model
{
    protected $table = 'operacao_logs';
    protected $guarded = [];
    protected $casts = [
    'data_alteracao' => 'datetime',
];
    public function operacao()
    {
        return $this->belongsTo(Operacao::class);
    }
}
