<?php

namespace App\Repositories\Transactions;

use App\Models\Settlement;

class UnauthorisedNewPartiesException
    extends UserCantAddException
{
    function __construct(
        Settlement $settlement,
        array $nonparticipatingPartyIds
    ) {
        parent::__construct($settlement);
        $this->message .=
            '. Reason: User attempted to add one or more'
            .' entities (id='
            .implode(', ',$nonparticipatingPartyIds)
            .') that were not previously participating.'
            .' This ability is limited to settlement owners'
            .'and admins.';
    }
}
