<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

use App\Models\Settlement;
use App\Models\Transaction;

class Request {
    public Settlement $settlement;
    public Transaction $transaction;
    public array $changes;
}
