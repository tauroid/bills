<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

class MultipleAbsoluteCurrenciesException extends \Exception {
    function __construct(array $absoluteCurrencies) {
        parent::__construct(
            'There is only expected to be one non-relative'
            .' currency (not percentage or share) in the'
            .' transaction - the currency of the transaction'
            .' total. Found: \''
            .implode('\', \'', $absoluteCurrencies).'\''
        );
    }
}
