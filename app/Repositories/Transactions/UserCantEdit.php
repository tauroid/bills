<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Models\User;

class UserCantEdit {
    private Transaction $transaction;
    private User $user;
    private array $datapaths;

    function __construct(
        Transaction $transaction, User $user, array $datapaths
    ) {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->datapaths = $datapaths;
    }

    function getTransaction() {
        return $this->transaction;
    }

    function getUser() {
        return $this->user;
    }

    function getDatapaths() {
        return $this->datapaths;
    }
}
