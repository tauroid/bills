<?php

namespace App\ExceptionHandling;

use App\Exceptions\FrontendException;

class FrontendExceptionHandler {
    public static function handle(callable $function) {
        try {
            return $function();
        } catch (FrontendException $exception) {
            return $exception->getAPIResponse()
                             ->toResponse();
        }
    }
}
