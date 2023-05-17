<?php

namespace App\Repositories\Transactions\Actions\DeleteTransaction;

use App\Models\Settlement;
use App\Models\Transaction;

class Request {
    public Settlement $settlement;
    public Transaction $transaction;
}
