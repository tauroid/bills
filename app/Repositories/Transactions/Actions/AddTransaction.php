<?php

namespace App\Repositories\Transactions\Actions;

use App\Models\Amount;
use App\Models\SettlementTransaction;
use App\Models\Transaction;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use App\Repositories\Action;
use App\Repositories\DBTransactionMiddleware;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AddTransaction extends Action {
    use AddTransaction\Validate;
    use AddTransaction\Authorise;

    protected static function middleware(): array {
        return [new DBTransactionMiddleware];
    }

    /** @return AddTransaction\Request */
    protected static function createRequest(...$args): mixed
    {
        $request = new AddTransaction\Request;
        $request->settlement = $args[0];
        $request->transactionData = $args[1];
        return $request;
    }

    /** @param AddTransaction\Request $request
     *  @return AddTransaction\RelevantData */
    protected static function getRelevantData(
        mixed $request
    ): mixed
    {
        $relevantData = new AddTransaction\RelevantData;

        $relevantData->parties = collect(array_map(
            fn ($attribution) => $attribution['party']['id'],
            array_merge(
                array_key_exists(
                    'froms', $request->transactionData)
                ? $request->transactionData['froms'] : [],
                array_key_exists(
                    'tos', $request->transactionData)
                ? $request->transactionData['tos'] : []
            )
        ));

        return $relevantData;
    }

    /** @param Request $request */
    protected static function toBeSaved(
        mixed $request, mixed $_, mixed $__, mixed $___
    ): array
    {
        $amount = Amount::instantiateComplete(
            $request->transactionData['amount']);

        $transaction = new Transaction([
            'type' => $request->transactionData['type'],
            'owner_id' => Auth::id()
        ]);

        $transaction->description =
            $request->transactionData['description'] ?? '';

        $transaction->setRelation('amount', $amount);

        $transaction->setRelation('froms', self::newFroms(
            $transaction, $request->transactionData['froms']));
        $transaction->setRelation('tos', self::newTos(
            $transaction, $request->transactionData['tos']));

        $settlementTransactionLink = new SettlementTransaction([
            'settlement_id' => $request->settlement->id
        ]);
        $settlementTransactionLink->setRelation(
            'transaction', $transaction);
        $transaction->setRelation('settlement_transactions', collect([
            $settlementTransactionLink
        ]));

        return [$transaction];
    }

    private static function newFroms(
        Transaction $transaction, array $fromsData
    ): Collection
    {
        return self::newAttributions(
            $transaction, $fromsData, 'froms');
    }

    private static function newTos(
        Transaction $transaction, array $tosData
    ): Collection
    {
        return self::newAttributions(
            $transaction, $tosData, 'tos');
    }

    private static function newAttributions(
        Transaction $transaction,
        array $attributionData,
        string $fromsOrTos
    ): Collection
    {
        $attributionClass = [
            'froms' => TransactionFrom::class,
            'tos' => TransactionTo::class,
        ][$fromsOrTos];

        return collect($attributionData)->map(
            function ($data) use
                ($transaction, $attributionClass)
            {
                $amount = Amount::instantiateComplete(
                    $data['amount']);
                $attribution = new $attributionClass([
                    'entity_id' => $data['party']['id'],
                ]);
                $attribution->setRelation(
                    'amount', $amount);
                $attribution->setRelation(
                    'transaction', $transaction);
                return $attribution;
            }
        );
    }
}
