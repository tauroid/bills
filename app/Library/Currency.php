<?php

namespace App\Library;

class Currency {
    public static function currencyIsAbsolute(
        string $currency
    ): bool
    {
        return !in_array($currency, ['percentage', 'share']);
    }
}
