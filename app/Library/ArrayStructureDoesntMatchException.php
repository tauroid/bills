<?php

namespace App\Library;

class ArrayStructureDoesntMatchException extends \Exception {
    function __construct(
        array $prefix, int $arrayIndex, mixed $key
    ) {
        parent::__construct(
            'During multiple traversal, the array at'
            .' index '.$arrayIndex.' doesn\'t contain the'
            .' key '.$key.' at prefix '.json_encode($prefix)
        );
    }
}
