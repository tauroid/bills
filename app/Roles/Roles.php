<?php

namespace App\Roles;

use ReflectionClass;

use App\Exceptions\HasNoAssertionFailureExceptionAttributeException;
use ReflectionException;

abstract class Roles {
    static function __callStatic(
        string $name, mixed $args): mixed
    {
        try {
            $reflectionClass =
                new ReflectionClass(static::class);
            $verifyMethodName = 'verify'.ucfirst($name);
            $reflectionMethod = $reflectionClass->getMethod(
                $verifyMethodName
            );
            if (!$reflectionMethod->isPublic()) {
                self::$name(...$args);
            }
            $attributes = $reflectionMethod->getAttributes(
                'App\\Attributes\\ThrowsWhenAssertionFails');
            if (count($attributes) === 0) {
                throw new HasNoAssertionFailureExceptionAttributeException(
                    $verifyMethodName);
            }
            $exceptionTypes = $attributes[0]->getArguments();
            try {
                $reflectionMethod->invoke(null, ...$args);
            } catch (\Exception $exception) {
                foreach ($exceptionTypes as $exceptionType) {
                    if (is_a($exception, $exceptionType)) {
                        return false;
                    }
                }
                throw $exception;
            }
            return true;
        } catch (ReflectionException) {
            self::$name(...$args);
        }
    }
}
