<?php

namespace App\Exceptions;

use Exception;

class AmountCurrencyMismatchException extends Exception {
    function __construct(string $thisCurrency, string $thatCurrency) {
        parent::__construct(
            "Currency '$thisCurrency' doesn't match"
            . " currency '$thatCurrency'");
    }
}
