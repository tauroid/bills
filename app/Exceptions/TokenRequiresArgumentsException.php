<?php

namespace App\Exceptions;

class TokenRequiresArgumentsException extends \Exception {
    function __construct(array $requiredArguments) {
        $argsString = '';

        foreach ($requiredArguments as $name => $type) {
            $argsString .= $type . ' ' . $name . ', ';
        }

        $argsString = substr($argsString, 0, -2);

        parent::__construct(
            "This token requires the following arguments: "
            . $argsString
        );
    }
}
