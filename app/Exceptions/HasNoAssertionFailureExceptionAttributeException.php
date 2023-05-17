<?php

namespace App\Exceptions;

class HasNoAssertionFailureExceptionAttributeException
    extends \Exception
{
    function __construct(string $methodName) {
        parent::__construct(
            'Method \''.$methodName.'\' has no '
            .'ThrowsWhenAssertionFails attribute.'
        );
    }
}
