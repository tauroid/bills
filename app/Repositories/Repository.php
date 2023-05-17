<?php

namespace App\Repositories;

abstract class Repository {
    abstract static function getActionClass(
        string $name
    ): ?string;

    static function __callStatic($name, $args): mixed {
        $actionClass = static::getActionClass($name);
        if (!$actionClass) {
            static::$name(...$args);
        }
        return $actionClass::execute(...$args);
    }
}
