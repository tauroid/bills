<?php

namespace App\Roles\Transaction;

class UserIsNotTransactionOwnerException extends \Exception {
    function __construct(int $user_id, int $transaction_id) {
        parent::__construct(
            'User (id='. $user_id .') is not the owner of '
            .'the transaction with id='. $transaction_id
        );
    }
}
