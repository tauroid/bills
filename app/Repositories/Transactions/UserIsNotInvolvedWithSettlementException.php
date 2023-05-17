<?php

namespace App\Repositories\Transactions;

use App\Models\Settlement;

class UserIsNotInvolvedWithSettlementException
    extends UserCantAddException
{
    function __construct(
        Settlement $settlement)
    {
        parent::__construct($settlement);
        $this->message .=
            '. Reason: The user is not a participant,'
            .' owner or admin of the settlement.';
    }
}
