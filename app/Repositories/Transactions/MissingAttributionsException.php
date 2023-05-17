<?php

namespace App\Repositories\Transactions;

use App\Exceptions\FrontendException;
use App\Http\APIResponse;
use App\Http\APIResponseType;

class MissingAttributionsException extends \Exception
    implements FrontendException
{
    private array $missingAttributionCategories;

    function __construct(array $missingAttributionCategories) {
        parent::__construct(
            'Missing \''.implode(
                's\' and \'', $missingAttributionCategories)
            .'s\' in transaction'
        );
        $this->missingAttributionCategories =
            $missingAttributionCategories;
    }

    function getAPIResponse(): APIResponse {
        return new APIResponse(
            APIResponseType::UserError,
            'The transaction isn\'t '
            .implode(' or ',
                     $this->missingAttributionCategories)
            .' anyone',
            [],
            400
        );
    }
}
