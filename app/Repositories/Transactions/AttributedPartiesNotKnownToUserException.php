<?php

namespace App\Repositories\Transactions;

use App\Models\Settlement;

class AttributedPartiesNotKnownToUserException
    extends UserCantAddException
{
    function __construct(
        Settlement $settlement,
        array $notKnownPartyIds
    ) {
        parent::__construct($settlement);
        $this->message .=
            '. Reason: User attempted to add one or more'
            .' entities (id='
            .implode(', ',$notKnownPartyIds)
            .') of which their account is not aware.'
            .' This is not currently permitted.';
    }
}
