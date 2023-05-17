<?php

namespace App\Tokens;

// DEPRECATED don't use this, I don't think PHP has the guts
//            to give this the guarantees it needs

abstract class Token {
    // so that it can't easily be illegally instantiated
    private function __construct() {}
    private function __clone() {}

    // $args should be stored in the concretion so that a token
    // receiver can check what the token applies to
    abstract public static function withToken(callable $continuation);
}
