<?php

namespace App\Exceptions;

class AssertionFailureArgumentIsNotAnExceptionException extends \Exception {
    function __construct(mixed $offendingArgument) {
        parent::__construct(
            $offendingArgument
            .' is not an instance of Exception');
    }
}
