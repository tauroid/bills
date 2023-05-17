<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;

class InvalidEditValueException extends \Exception {
    function __construct(Transaction $transaction, mixed $path, mixed $value) {
        parent::__construct(
            'Invalid new value '.$value.' for path '
            .is_array($path) ? implode('.',$path) : $path
            .' in transaction (id='.$transaction->id.')'
        );
    }
}
