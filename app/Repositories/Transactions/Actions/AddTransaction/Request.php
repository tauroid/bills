<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

use App\Models\Settlement;

class Request {
    public Settlement $settlement;
    public array $transactionData;
}
