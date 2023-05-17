<?php

namespace App\Repositories\Transactions\Actions\DeleteTransaction;

use App\Models\Settlement;
use App\Models\Transaction;

class UserDoesNotHaveAuthorityToDeleteException
    extends \Exception
{
    function __construct(
        Settlement $settlement, Transaction $transaction)
    {
        parent::__construct(
            'The user is not an owner or admin of the'
            .' settlement id='.$settlement->id.', nor the'
            .' owner of the transaction id='.$transaction->id
        );
    }
}
