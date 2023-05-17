<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

class IllegalAttributionChangesException extends \Exception {
    function __construct(array $illegalChanges) {
        parent::__construct(
            'The user attempted to create, edit or delete'
            .' transaction attributions corresponding to'
            .' other users. This ability is restricted to owners'
            .' and admins. Here is the breakdown:'
            .json_encode($illegalChanges)
        );
    }
}
