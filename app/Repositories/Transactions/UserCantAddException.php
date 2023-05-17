<?php

namespace App\Repositories\Transactions;

use App\Models\Settlement;

class UserCantAddException extends \Exception {
    function __construct(
        Settlement $settlement)
    {
        parent::__construct(
            'User does not have permission to add this'
            .' transaction to the settlement (id='
            .$settlement->id.')'
        );
    }
}
