<?php

namespace App\Exceptions;

use App\Models\User;

class InvalidAssignedSettlementOwner extends \Exception {
    function __construct(User $user, User $newOwner) {
        parent::__construct(
            'User id ' . $newOwner->id . ' is not a valid candidate'
            . ' for settlement ownership transfer from user id'
            . ' ' . $user->id
        );
    }
}
