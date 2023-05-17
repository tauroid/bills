<?php

namespace App;

class ExceptionsToBoolean {
    static function exceptionsToBoolean(
        callable $function, array $exceptionTypes
    ): bool
    {
        try {
            $function();
        } catch (\Exception $exception) {
            foreach (
                static::VALIDATION_FAILURE_EXCEPTIONS
                as $exceptionType
            ) {
                if (is_a($exception, $exceptionType)) {
                    return false;
                }
            }
            throw $exception;
        }
        return true;
    }
}
