<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

use App\Models\Settlement;
use App\Models\Transaction;
use App\Repositories\Transactions\Actions\AddTransaction\AuthorisationRelevantData as AddTransactionARD;
use App\Roles\Transaction as TransactionRoles;
use Illuminate\Support\Collection;

class AuthorisationRelevantData extends AddTransactionARD {
    public Transaction $transaction;

    public bool $userIsTransactionOwner;

    public Collection $changedParties;

    // only relevant if the user is only a participant
    public ?array $illegalAttributionChanges;

    static function
        fillEditTransactionAuthorisationRelevantData(
            self $relevantData,
            Settlement $settlement,
            Transaction $transaction,
            Collection $newParties,
            Collection $deletedParties,
        ): void
    {
        parent::fillFromSettlementAndNewParties(
            $relevantData, $settlement, $newParties);

        $relevantData->transaction = $transaction;

        $relevantData->userIsTransactionOwner =
            TransactionRoles::userIsTransactionOwner(
                $transaction);

        $relevantData->changedParties =
            $newParties->merge($deletedParties);
    }
}
