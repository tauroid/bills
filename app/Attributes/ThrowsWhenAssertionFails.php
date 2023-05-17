<?php

namespace App\Attributes;

use App\Exceptions\AssertionFailureArgumentIsNotAnExceptionException;

#[\Attribute]
class ThrowsWhenAssertionFails {
    private array $exceptions;

    public function __construct(...$args) {
        foreach ($args as $arg) {
            if (!is_a($arg, \Exception::class)) {
                throw new AssertionFailureArgumentIsNotAnExceptionException($arg);
            }
        }
        $this->exceptions = $args;
    }

    public function getExceptions(): array {
        return $this->$exceptions;
    }
}
