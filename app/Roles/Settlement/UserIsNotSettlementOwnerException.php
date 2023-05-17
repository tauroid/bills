<?php

namespace App\Roles\Settlement;

class UserIsNotSettlementOwnerException extends \Exception {
    function __construct(int $user_id, int $settlement_id) {
        parent::__construct(
            'User (id='. $user_id .') is not the owner of '
            .'the settlement with id='. $settlement_id
        );
    }
}
