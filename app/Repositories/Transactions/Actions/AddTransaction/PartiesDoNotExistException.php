<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

class PartiesDoNotExistException extends \Exception {
    function __construct(array $parties) {
        parent::__construct(
            'The following party ids to the new transaction'
            .' do not exist: '
            .implode(', ', array_map(
                fn ($party) => 'id='.$party['id'],
                $parties))
        );
    }
}
