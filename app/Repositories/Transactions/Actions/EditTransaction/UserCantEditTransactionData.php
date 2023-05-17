<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

use App\Models\Transaction;

class UserCantEditTransactionData extends \Exception {
    function __construct(
        Transaction $transaction,
        string $field
    ) {
        parent::__construct(
            'User isn\'t permitted to edit the '
            .'\''.$field.'\' of transaction (id='
            .$transaction->id.')'
        );
    }
}
