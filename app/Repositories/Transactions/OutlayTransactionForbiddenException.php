<?php

namespace App\Repositories\Transactions;

use App\Models\Settlement;

class OutlayTransactionForbiddenException
    extends UserCantAddException
{
    function __construct(
        Settlement $settlement, array $transactionData
    ) {
        parent::__construct($settlement, $transactionData);
        $this->message .=
            '. Reason: User is not permitted to create'
            .' an outlay (to be settled) transaction.'
            .' This ability is limited to settlement owners'
            .' and admins.';
    }
}
