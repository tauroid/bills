<?php

namespace App\Repositories;

class UndefinedZoneMethodException extends \Exception {
    function __construct(
        string $repositoryName,
        string $zoneName,
        string $methodName
    ) {
        parent::__construct(
            'Method \''.$methodName.'\' does not exist in'
            .' the \''.$zoneName.'\' zone of the'
            .' \''.$repositoryName.'\' repository'
        );
    }
}
