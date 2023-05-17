<?php

namespace App\Repositories\Transactions;

class DescriptionIsntStringException extends \Exception {
    function __construct(mixed $description) {
        parent::__construct(
            'The transaction description field is supposed to'
            .' be a string, but got '.gettype($description)
        );
    }
}
