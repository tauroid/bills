<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

use App\Exceptions\FrontendException;
use App\Http\APIResponse;
use App\Http\APIResponseType;

class MissingTransactionAmountException extends \Exception
    implements FrontendException
{
    function __construct() {
        parent::__construct(
            'The transaction doesn\'t have an amount set'
        );
    }

    function getAPIResponse(): APIResponse {
        return new APIResponse(
            APIResponseType::UserError,
            'The transaction doesn\'t have an amount set',
            [],
            400
        );
    }
}
