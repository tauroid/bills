<?php

namespace App\Exceptions;

use App\Http\APIResponse;

interface FrontendException {
    function getAPIResponse(): APIResponse;
}
