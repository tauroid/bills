<?php

namespace App;

class CallWithMiddleware {
    static function callWithMiddleware(
        array $middleware, callable $function,
        ...$args
    ) {
        if (count($middleware) > 0) {
            return $middleware[0](
                fn () => self::callWithMiddleware(
                    array_slice($middleware, 1),
                    $function
                ),
                ...$args);
        } else {
            return $function(...$args);
        }
    }
}
