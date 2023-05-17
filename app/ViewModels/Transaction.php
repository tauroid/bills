<?php

namespace App\ViewModels;

use App\DerivedModels\TransactionAbsoluteAmounts;
use App\Models\Settlement;
use App\Models\Transaction as TransactionModel;
use App\Roles\Settlement as SettlementRoles;
use App\Roles\Transaction as TransactionRoles;

class Transaction {
    static function toArray(
        Settlement $settlement,
        TransactionModel $transaction) {
        $absolute = new TransactionAbsoluteAmounts($transaction);

        return [
            'id' => $transaction->id,
            'type' => $transaction->type,
            'inconsistent' => $transaction->isInconsistent(),
            'full_authority' =>
            SettlementRoles::userIsOwner($settlement)
            || SettlementRoles::userIsAdmin($settlement)
            || TransactionRoles::userIsTransactionOwner(
                $transaction),
            'description' => $transaction->description,
            'froms' => $transaction->froms
                ->zip($absolute->getFroms())->map(
                    fn ($pair) => Attribution::toArray(
                        $settlement,
                        $transaction,
                        'from',...$pair
                    )),
            'tos' => $transaction->tos
                ->zip($absolute->getTos())->map(
                    fn ($pair) => Attribution::toArray(
                        $settlement,
                        $transaction,
                        'to',...$pair
                    )),
            'amount' => [
                'currency' => $transaction->amount->currency,
                $transaction->amount->currencyAmountPropertyName() =>
                $transaction->amount->currency_amount
            ],
            'created_at' => $transaction->created_at
        ];
    }
}
