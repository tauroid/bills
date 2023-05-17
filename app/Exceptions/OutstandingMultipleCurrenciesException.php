<?php

namespace App\Exceptions;

class OutstandingMultipleCurrenciesException extends \Exception {
    function __construct(array $currencies) {
        parent::__construct(
            'Settlement transactions involve multiple currencies'
            .'('.implode(',', $currencies).')'
            .', so a single outstanding balance is not possible'
            .', and multiple outstanding balances would not'
            .' necessarily be informative (someone may have been'
            .' happily repaid in a different currency)');
    }
}
