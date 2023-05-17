<?php

namespace App\DerivedModels;

use App\DerivedModels\TransactionAbsoluteAmounts\AbsoluteAttributionCalculator;
use App\Models\Amount;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionAbsoluteAmounts {
    private Collection $froms;
    private mixed $amount;
    private Collection $tos;

    function __construct(Transaction $transaction) {
        $this->froms =
            AbsoluteAttributionCalculator
                ::calculateAbsoluteAttributions(
                    $transaction->amount,
                    $transaction->froms);

        $this->amount = $transaction->amount;

        $this->tos =
            AbsoluteAttributionCalculator
                ::calculateAbsoluteAttributions(
                    $transaction->amount,
                    $transaction->tos);
    }

    function getFroms(): Collection {
        return $this->froms;
    }

    function getAmount(): Amount {
        return $this->amount;
    }

    function getTos(): Collection {
        return $this->tos;
    }
}
