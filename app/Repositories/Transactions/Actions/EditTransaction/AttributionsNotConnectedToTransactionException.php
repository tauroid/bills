<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

use App\Models\Transaction;

class AttributionsNotConnectedToTransactionException
    extends \Exception
{
    function __construct(
        Transaction $transaction,
        array $unconnectedEdits)
    {
        parent::__construct(
            'These attributions are not part of the'
            .' selected transaction (id='.$transaction->id
            .'): '
            .implode(', ',
                     collect($unconnectedEdits)
                     ->map(fn ($edit, $id) => 'id='.$id)
                     ->toArray())
        );
    }
}
