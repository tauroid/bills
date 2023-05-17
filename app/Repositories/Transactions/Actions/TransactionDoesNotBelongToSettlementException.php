<?php

namespace App\Repositories\Transactions\Actions;

use App\Models\Settlement;
use App\Models\Transaction;

class TransactionDoesNotBelongToSettlementException extends \Exception {
    function __construct(
        Settlement $settlement, Transaction $transaction)
    {
        parent::__construct(
            'Transaction id='.$transaction->id.' is not'
            .' part of settlement id='.$settlement->id
        );
    }
}
