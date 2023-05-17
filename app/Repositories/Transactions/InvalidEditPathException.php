<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;

class InvalidEditPathException extends \Exception {
    function __construct(Transaction $transaction, mixed $path) {
        parent::__construct(
            'Transaction (id='.$transaction->id.') does not'
            .' contain path '
            .is_array($path) ? implode('.',$path) : $path
        );
    }
}
