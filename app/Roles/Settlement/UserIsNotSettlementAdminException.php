<?php

namespace App\Roles\Settlement;

class UserIsNotSettlementAdminException extends \Exception {
    function __construct(int $user_id, int $settlement_id) {
        parent::__construct(
            'User (id='. $user_id .') is not an admin of '
            .'the settlement with id='. $settlement_id
        );
    }
}
