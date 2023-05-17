<?php

namespace App\Repositories;

class UndefinedRepositoryMethodException extends \Exception {
    function __construct(
        string $repositoryName, string $methodName
    ) {
        parent::__construct(
            'Method \''.$methodName.'\' does not exist in'
            .' the \''.$repositoryName.'\' repository'
        );
    }
}
