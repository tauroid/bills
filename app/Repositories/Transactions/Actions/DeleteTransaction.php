<?php

namespace App\Repositories\Transactions\Actions;

use App\Repositories\Action;
use App\Repositories\Transactions\Actions\DeleteTransaction\UserDoesNotHaveAuthorityToDeleteException;
use App\Roles\Settlement as SettlementRoles;
use App\Roles\Transaction as TransactionRoles;

class DeleteTransaction extends Action {
    protected static function createRequest(...$args): mixed
    {
        $request = new DeleteTransaction\Request;
        $request->settlement = $args[0];
        $request->transaction = $args[1];
        return $request;
    }

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

        return null;
    }

    /** @param DeleteTransaction\Request $request */
    protected static function authoriseRequest(
        mixed $request, mixed $_, mixed $__, mixed $___
    ): mixed
    {
        if (!SettlementRoles::userIsOwner($request->settlement)
            && !SettlementRoles::userIsAdmin(
                $request->settlement)
            && !TransactionRoles::userIsTransactionOwner(
                $request->transaction))
        {
            throw new UserDoesNotHaveAuthorityToDeleteException(
                $request->settlement, $request->transaction
            );
        }

        return null;
    }

    protected static function deletions(
        mixed $request, mixed $_, mixed $__, mixed $___
    ): array
    {
        return [$request->transaction];
    }

    protected static function validateChanges(
        array $_, array $__
    ): mixed
    {
        return null;
    }
}
