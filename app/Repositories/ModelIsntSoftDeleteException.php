<?php

namespace App\Repositories;

class ModelIsntSoftDeleteException extends \Exception {
    function __construct(string $model) {
        parent::__construct(
            'Tried to delete a \''.$model.'\' in the'
            .' database but it doesn\'t have soft'
            .' deletion enabled'
        );
    }
}
