<?php

namespace App\Repositories\Transactions\Actions\EditTransaction;

use App\Library\ArrayTraversalContext;
use App\Library\ArrayTraverser;
use App\Models\Entity;
use App\Models\Transaction;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use App\Models\User;
use App\Repositories\Transactions\UserCantEdit;
use App\Repositories\Transactions\UserCantEditException;
use App\Repositories\Transactions\UserIsNotInvolvedWithSettlementException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

trait Authorise {
    private static function getNewParties(
        array $attributionsChanges
    ): Collection
    {
        $newParties = new Collection;
        if (array_key_exists('newItems', $attributionsChanges))
        {
            $newParties =
                $newParties->merge(
                    collect($attributionsChanges ['newItems'])
                    ->map(fn ($attribution) =>
                          $attribution['party']['id'])
                );
        }
        if (array_key_exists('edit', $attributionsChanges))
        {
            $newParties =
                $newParties->merge(
                    collect($attributionsChanges ['edit'])
                    ->filter(
                        fn ($edit) =>
                        array_key_exists('party', $edit)
                    )->map(fn ($edit) =>
                           $edit['party']['id'])
                );
        }
        return $newParties;
    }

    /** @param Collection<TransactionFrom|TransactionTo> $attributions */
    private static function editIsIllegal(
        Collection $attributions,
        int $attributionId,
        array $edit
    ): bool
    {
        $entity = $attributions->firstWhere(
            'id', $attributionId)->entity;
        $user = User::find(Auth::id());
        if (!$entity->correspondsToUser($user)) return true;
        if (array_key_exists('party', $edit)) {
            $newEntity = Entity::find($edit['party']['id']);
            if (!$newEntity->correspondsToUser($user))
            {return true;}
        }
        return false;
    }

    // assume that the user is a participant but not
    // otherwise privileged
    private static function getIllegalAttributionChanges(
        Transaction $transaction,
        array $changes
    ): array
    {
        $illegalChanges = [];
        $processNewItem = function ($key) use (&$illegalChanges)
        {
            return function ($_, $attribution)
                use (&$illegalChanges, $key)
            {
                $entity = Entity::find(
                    $attribution['party']['id']);
                if (!$entity->correspondsToUser(
                    Auth::user()))
                {
                    $illegalChanges[$key]
                    ['newItems'][]= $attribution;
                }
            };
        };
        $processEdit = function ($key) use (&$illegalChanges) {
            return function (
                $prefix, $edit,
                $contextData
            )
                use (&$illegalChanges, $key)
            {
                if (self::editIsIllegal(
                    $contextData, end($prefix),
                    $edit))
                {
                    $illegalChanges[$key]['edit']
                    [end($prefix)] = $edit;
                }
            };
        };
        $processDelete = function ($key) use (&$illegalChanges) {
            return function (
                $_, $id, $contextData)
                use (&$illegalChanges, $key)
            {
                $user = User::find(Auth::id());
                if (!$contextData
                    ->firstWhere('id',$id)
                    ->entity
                    ->correspondsToUser($user))
                {
                    $illegalChanges[$key]
                    ['delete'][]= $id;
                }
            };
        };

        foreach ($changes as $key => $value) {
            if (!in_array($key, ['fromsChanges','tosChanges'])) {
                continue;
            }
            ArrayTraverser::traverse(
                new ArrayTraversalContext(
                    reducer: fn ($ctx, $type) => [
                        'newItems' => new ArrayTraversalContext(
                            prototype: $ctx,
                            reducer: fn ($ctx) =>
                            new ArrayTraversalContext(
                                prototype: $ctx,
                                function: $processNewItem($key),
                                dontRecurse: fn () => true)
                        ),
                        'edit' => new ArrayTraversalContext(
                            prototype: $ctx,
                            reducer: fn ($ctx) =>
                            new ArrayTraversalContext(
                                prototype: $ctx,
                                function: $processEdit($key),
                                dontRecurse: fn () => true)
                        ),
                        'delete' => new ArrayTraversalContext(
                            prototype: $ctx,
                            reducer: fn ($ctx) =>
                            new ArrayTraversalContext(
                                prototype: $ctx,
                                function: $processDelete($key),
                                dontRecurse: fn () => true)
                        )
                    ][$type],
                    data: $transaction->{substr($key,0,-7)}
                ),
                $value
            );
        }
        return $illegalChanges;
    }

    /** @param Request $request */
    protected static function getAuthorisationRelevantData(
        mixed $request, mixed $_
    ): mixed
    {
        $newParties = new Collection;
        $deletedParties = new Collection;

        foreach (['fromsChanges','tosChanges']
                 as $attributionsChanges)
        {
            if (array_key_exists(
                $attributionsChanges,
                $request->changes))
            {
                $newParties =
                    $newParties->merge(
                        self::getNewParties(
                            $request->changes
                            [$attributionsChanges])
                    );

                if (array_key_exists(
                    'delete',
                    $request->changes
                    [$attributionsChanges]))
                {
                    $deletedParties =
                        $deletedParties->merge(collect(
                            $request->changes
                            [$attributionsChanges]
                            ['delete']
                        ));
                }
            }
        }

        $relevantData = new AuthorisationRelevantData;

        AuthorisationRelevantData
            ::fillEditTransactionAuthorisationRelevantData(
                $relevantData,
                $request->settlement,
                $request->transaction,
                $newParties,
                $deletedParties,
            );

        if (!$relevantData->userIsOwner
            && !$relevantData->userIsAdmin
            && !$relevantData->userIsTransactionOwner
            && $relevantData->userIsParticipant
        ) {
            $relevantData->illegalAttributionChanges =
                self::getIllegalAttributionChanges(
                    $request->transaction,
                    $request->changes
                );
        }

        return $relevantData;
    }

    static function checkUserCanEditFields(
        Request $request,
        AuthorisationRelevantData $relevantData
    ): void
    {
        if ($relevantData->userIsOwner) return;
        if ($relevantData->userIsAdmin) return;
        if ($relevantData->userIsTransactionOwner) return;
        if (!$relevantData->userIsParticipant) {
            throw new UserIsNotInvolvedWithSettlementException(
                $relevantData->settlement
            );
        }

        $illegalPaths = [];
        foreach (array_keys($request->changes) as $key) {
            if (!in_array($key, ['fromsChanges','tosChanges']))
            {
                $illegalPaths []= [$key];
            }
        }
        if (!empty($illegalPaths)) {
            throw new UserCantEditException(
                new UserCantEdit(
                    $request->transaction, Auth::user(),
                    $illegalPaths
                )
            );
        }

        if (!empty($relevantData->illegalAttributionChanges))
        {
            throw new IllegalAttributionChangesException(
                $relevantData->illegalAttributionChanges
            );
        }
    }

    /**
     * @param Request $request
     * @param AuthorisationRelevantData $relevantData */
    static function authoriseRequest(
        mixed $request, mixed $relevantData,
        mixed $_, mixed $__
    ): mixed
    {
        $relevantData->checkUserCanAddNewParties();

        self::checkUserCanEditFields($request, $relevantData);

        return null;
    }

}
