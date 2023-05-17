<?php

namespace App\Roles\Settlement;

use App\Models\Settlement;
use App\Models\User;

class UserIsNotSettlementParticipantException extends \Exception {
    function __construct(Settlement $settlement, User $user) {
        parent::__construct(
            'User (id='.$user->id.') is not a participant'
            .' in the settlement (id='.$settlement->id.')');
    }
}
