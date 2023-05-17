<?php

namespace App\Repositories\Transactions\Actions;

class RelativeTransactionCurrencyException extends \Exception {
    function __construct(string $currency) {
        parent::__construct(
            'The transaction currency cannot be relative'
            .' (share or percentage). Found \''.$currency.'\''
        );
    }
}
