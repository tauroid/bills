<?php

namespace App\Repositories\Transactions\Validation;

class Limits {
    const DESCRIPTION_LENGTH = 10000;
    const FILE_SIZE = 10e6;
    const USER_STORAGE = 200e6;
}
