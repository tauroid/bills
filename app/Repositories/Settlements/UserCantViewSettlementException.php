<?php

namespace App\Repositories\Settlements;

use App\Models\Settlement;

class UserCantViewSettlementException extends \Exception {
    function __construct(Settlement $settlement) {
        parent::__construct(
            'User can\'t view settlement id='.$settlement->id
            .' - they aren\'t a participant, and they aren\'t'
            .' an admin or owner'
        );
    }
}
