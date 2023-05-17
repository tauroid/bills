<?php

namespace App\Repositories;

use App\Models\Settlement;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Repository;
use App\Repositories\Transactions\Actions\AddTransaction;
use App\Repositories\Transactions\Actions\EditTransaction;
use App\Repositories\Transactions\Actions\DeleteTransaction;
use App\Repositories\Transactions\UserCanEdit;
use App\Repositories\Transactions\UserCantEdit;
use App\Repositories\Transactions\UserCantEditException;
use App\Roles\Settlement as SettlementRoles;
use App\Roles\Transaction as TransactionRoles;
use Illuminate\Support\Facades\Auth;

/*
 * @method static void addTransaction(Settlement $settlement, array $transactionData)
 * @method static void editTransaction(Settlement $settlement, Transaction $transaction, array $changes)
 * @method static void deleteTransaction(Settlement $settlement, Transaction $transaction)
 */
class Transactions extends Repository {
    static function getActionClass(string $name): ?string {
        return [
            'addTransaction' => AddTransaction::class,
            'editTransaction' => EditTransaction::class,
            'deleteTransaction' => DeleteTransaction::class
        ][$name];
    }

    static function verifyUserCanEdit(
        Settlement $settlement,
        Transaction $transaction,
        array $changes
    ) {
        $canUserEdit = self::userCanEdit(
            $settlement, $transaction, $changes);
        if (is_a($canUserEdit, UserCantEdit::class))
        {
            throw new UserCantEditException($canUserEdit);
        }
    }

    static function userCanEdit(
        Settlement $settlement,
        Transaction $transaction,
        array $path
    ): UserCanEdit|UserCantEdit {
        if (SettlementRoles::userIsOwner($settlement)) {
            return new UserCanEdit;
        } elseif (SettlementRoles::userIsAdmin($settlement)) {
            return new UserCanEdit;
        } elseif (
            TransactionRoles::userIsTransactionOwner(
                $transaction))
        {
            return new UserCanEdit;
        } else {
            $user = User::find(Auth::id());
            if (count($path) < 2) {
                return new UserCantEdit(
                    $transaction, $user, $path);
            }
            if (in_array($path[0], ['froms','tos']))
            {
                $attributionClass =
                    'App\Models\Transaction'
                    .ucfirst(substr($path[0],0,-1));

                $attribution = $attributionClass::find($path[1]);

                if ($attribution->entity
                    ->correspondsToUser($user))
                {
                    return new UserCanEdit;
                } else {
                    return new UserCantEdit(
                        $transaction, $user, [$path]);
                }
            }
        }
        return new UserCantEdit(
            $transaction, Auth::user(), ['everything']);
    }

}
