<?php

namespace App\Repositories\Transactions;

use App\Repositories\Transactions\Validation\Limits;

class DescriptionTooLongException extends \Exception {
    function __construct(string $description) {
        parent::__construct(
            'Transaction description is too long: max is'
            .' '.Limits::DESCRIPTION_LENGTH.' characters'
            .' but received '.strlen($description)
        );
    }
}
