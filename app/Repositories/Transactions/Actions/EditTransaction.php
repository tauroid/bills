<?php

namespace App\Repositories\Transactions\Actions;

use App\Models\Amount;
use App\Models\Entity;
use App\Models\Transaction;
use App\Repositories\Action;
use App\Repositories\DBTransactionMiddleware;

class EditTransaction extends Action {
    use EditTransaction\Validate;
    use EditTransaction\Authorise;

    protected static function middleware(): array {
        return [new DBTransactionMiddleware];
    }

    protected static function createRequest(...$args): mixed {
        $request = new EditTransaction\Request;
        $request->settlement = $args[0];
        $request->transaction = $args[1];
        $request->changes = $args[2];
        return $request;
    }

    private static function applyAttributionsChanges(
        Transaction $transaction,
        string $attributionType,
        array $changes
    ): void
    {
        $oldAttributions =
            $transaction->{$attributionType}->mapWithKeys(
                fn($attribution) =>
                [$attribution->id => $attribution]
            );

        $attributionClass =
            'App\Models\Transaction'
            .ucfirst(substr($attributionType,0,-1));

        foreach ($changes['delete'] ?? [] as $id) {
            $oldAttributions->get($id)->delete();
            $oldAttributions->pull($id);
        }

        foreach ($changes['edit'] ?? [] as $id => $edit) {
            $attribution = $oldAttributions->get($id);
            if (array_key_exists('amount', $edit)) {
                $amount = Amount::instantiateComplete(
                    $edit['amount']
                );
                $attribution->setRelation('amount', $amount);
            }
            if (array_key_exists('party', $edit)) {
                $attribution->entity_id = $edit['party']['id'];
            }
        }

        $newAttributions = $oldAttributions->values();

        foreach ($changes['newItems'] ?? [] as $newItem) {
            $amount = Amount::instantiateComplete(
                $newItem['amount']
            );
            $attribution = new $attributionClass([
                'entity_id' => $newItem['party']['id']
            ]);
            $attribution->setRelation('amount', $amount);
            $attribution->setRelation(
                'transaction', $transaction);
            $newAttributions->push($attribution);
        }

        $transaction->setRelation(
            $attributionType, $newAttributions);
    }

    /** @param EditTransaction\Request $request */
    protected static function toBeSaved(
        mixed $request, mixed $_, mixed $__, mixed $___
    ): array
    {
        $transaction = $request->transaction;
        $changes = $request->changes;
        if (array_key_exists('changedAmount', $changes)
            && $changes['changedAmount']
        ) {
            $amount = Amount::instantiateComplete(
                $changes['changedAmount']
            );
            $transaction->setRelation('amount', $amount);
        }
        if (array_key_exists('changedType', $changes)
            && $changes['changedType']
        ) {
            $transaction->type = $changes['changedType'];
        }
        if (array_key_exists('fromsChanges', $changes)
            && $changes['fromsChanges']
        ) {
            self::applyAttributionsChanges(
                $transaction, 'froms', $changes['fromsChanges']
            );
        }
        if (array_key_exists('tosChanges', $changes)
            && $changes['tosChanges']
        ) {
            self::applyAttributionsChanges(
                $transaction, 'tos', $changes['tosChanges']
            );
        }
        if (array_key_exists('changedDescription', $changes)
            && $changes['changedDescription']
        ) {
            $transaction->description =
                $changes['changedDescription'];
        }
        return [$transaction];
    }
}
