<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function toFloat($value): float
    {
        if (empty($value)) return 0.0;

        if (is_numeric($value)) return (float) $value;

        $cleanValue = str_replace(['R$', ' ', "\xA0"], '', $value);

        if (str_contains($cleanValue, ',')) {
            $cleanValue = str_replace('.', '', $cleanValue);
            $cleanValue = str_replace(',', '.', $cleanValue);
        }

        $finalValue = preg_replace('/[^0-9.]/', '', $cleanValue);

        return (float) ($finalValue ?: 0.0);
    }
}
