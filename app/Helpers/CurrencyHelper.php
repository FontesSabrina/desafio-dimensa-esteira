<?php

namespace App\Helpers;

class CurrencyHelper
{

 public static function toFloat($value): float
{
    if (empty($value)) return 0.0;
    if (is_numeric($value)) return (float) $value;

    // Se tiver vírgula, tratamos como padrão brasileiro (1.200,50)
    if (str_contains($value, ',')) {
        $cleanValue = str_replace('.', '', $value); // Tira o ponto de milhar
        $cleanValue = str_replace(',', '.', $cleanValue); // Troca a vírgula decimal por ponto
        return (float) $cleanValue;
    }

    return (float) $value;
}
}
