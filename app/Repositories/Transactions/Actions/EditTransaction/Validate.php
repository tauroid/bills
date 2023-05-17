<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

use App\Models\Transaction;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use App\Repositories\Transactions\Actions\TransactionDoesNotBelongToSettlementException;
use App\Repositories\Transactions\DescriptionIsntStringException;
use Illuminate\Support\Collection;

/*
 * * There has to remain only one absolute currency
 * * The transaction currency has to be absolute
 */

trait Validate {
    private static function unconnectedAttributions(
        Transaction $transaction,
        string $attributionClass,
        Collection $attributionIds
    ): Collection
    {
        return $attributionIds->filter(
            function ($id) use
                ($transaction, $attributionClass)
            {
                $attribution = $attributionClass::find($id);
                return $attribution->transaction_id
                    !== $transaction->id;
            });
    }

    private static function validateAttributionsEdits(
        Transaction $transaction,
        string $attributionClass,
        array $edits
    ): void
    {
        $unconnectedEdits = self::unconnectedAttributions(
            $transaction, $attributionClass,
            collect($edits)->keys()
        );
        if (!$unconnectedEdits->isEmpty()) {
            throw new AttributionsNotConnectedToTransactionException(
                $transaction,
                $unconnectedEdits->toArray()
            );
        }
    }

    private static function validateAttributionsDeletes(
        Transaction $transaction,
        string $attributionClass,
        array $deletes
    ): void
    {
        $unconnectedDeletes = self::unconnectedAttributions(
            $transaction, $attributionClass,
            collect($deletes)
        );
        if (!$unconnectedDeletes->isEmpty()) {
            throw new AttributionsNotConnectedToTransactionException(
                $transaction,
                $unconnectedDeletes->toArray()
            );
        }
    }

    private static function validateAttributionsChanges(
        Transaction $transaction,
        string $attributionClass,
        array $attributions
    ): void
    {
        if (array_key_exists('edit', $attributions)) {
            self::validateAttributionsEdits(
                $transaction, $attributionClass,
                $attributions['edit']
            );
        }

        if (array_key_exists('delete', $attributions)) {
            self::validateAttributionsDeletes(
                $transaction, $attributionClass,
                $attributions['delete']
            );
        }
    }

    /** @param Request $request */
    protected static function validateRequest(
        mixed $request, mixed $_, mixed $__
    ): mixed
    {
        if ($request->transaction->settlements->where(
            'id', $request->settlement->id)->isEmpty())
        {
            throw new TransactionDoesNotBelongToSettlementException(
                $request->settlement, $request->transaction
            );
        }

        if (array_key_exists(
            'fromsChanges', $request->changes))
        {
            self::validateAttributionsChanges(
                $request->transaction,
                TransactionFrom::class,
                $request->changes['fromsChanges']
            );
        }

        if (array_key_exists(
            'tosChanges', $request->changes))
        {
            self::validateAttributionsChanges(
                $request->transaction,
                TransactionTo::class,
                $request->changes['tosChanges']
            );
        }

        if (array_key_exists(
            'changedDescription', $request->changes)
            && !is_null($request->changes['changedDescription'])
            && !is_string(
                $request->changes['changedDescription']))
        {
            throw new DescriptionIsntStringException(
                $request->changes['changedDescription']);
        }

        return null;
    }

    protected static function validateChanges(
        array $toBeSaved,
        array $deletions
    ): mixed
    {
        [$transaction] = $toBeSaved;
        $transaction->validate();
        return null;
    }
}
