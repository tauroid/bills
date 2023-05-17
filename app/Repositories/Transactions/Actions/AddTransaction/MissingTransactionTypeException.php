<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

use App\Exceptions\FrontendException;
use App\Http\APIResponse;
use App\Http\APIResponseType;

class MissingTransactionTypeException extends \Exception
    implements FrontendException
{
    function __construct() {
        parent::__construct(
            'The transaction needs a type (payment or service)'
        );
    }

    function getAPIResponse(): APIResponse {
        return new APIResponse(
            APIResponseType::UserError,
            'Is the transaction is a payment or a service?',
            [],
            400
        );
    }
}
