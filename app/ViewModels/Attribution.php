<?php

namespace App\ViewModels;

use App\DerivedModels\TransactionAbsoluteAmounts\Attribution as AbsoluteAttribution;
use App\Models\Settlement;
use App\Models\Transaction;
use App\Models\TransactionTo;
use App\Models\TransactionFrom;
use App\Repositories\Transactions as TransactionsRepository;
use App\Repositories\Transactions\UserCanEdit;

class Attribution {
    static function toArray(
        Settlement $settlement,
        Transaction $transaction,
        string $attributionType,
        TransactionFrom|TransactionTo $attribution,
        AbsoluteAttribution $absoluteAttribution
    ) {
        $absoluteAmount = $absoluteAttribution->getAmount();

        $associatedUserName =
            $attribution->entity->dummy_entity?->real_entity
            ? $attribution->entity->dummy_entity
                          ->real_entity->names[0]->name
            : null;

        return [
            'id' => $attribution->id,
            'name' => $attribution->entity->names[0]->name,
            'associatedUserName' => $associatedUserName,
            'displayName' => $attribution->entity->displayName(),
            'can_edit' => is_a(
                TransactionsRepository::userCanEdit(
                    $settlement,
                    $transaction,
                    [$attributionType.'s',$attribution->id]),
                UserCanEdit::class
            ),
            'amount' => [
                'currency' => $attribution->amount->currency,
                $attribution->amount->currencyAmountPropertyName() =>
                $attribution->amount->currency_amount
            ],
            'absolute_amount' => [
                'currency' => $absoluteAmount->currency,
                $absoluteAmount->currencyAmountPropertyName() =>
                $absoluteAmount->currency_amount
            ]
        ];
    }
}
