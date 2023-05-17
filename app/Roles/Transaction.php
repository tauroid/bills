<?php

namespace App\Roles;

use App\Attributes\ThrowsWhenAssertionFails;
use App\Models\Transaction as TransactionModel;
use App\Roles\Transaction\UserIsNotTransactionOwnerException;
use Illuminate\Support\Facades\Auth;

/**
 * class Transaction
 * @method static bool userIsTransactionOwner(TransactionModel $transaction)
 */
class Transaction extends Roles {
    #[ThrowsWhenAssertionFails(
        UserIsNotTransactionOwnerException::class)]
    static function verifyUserIsTransactionOwner(
        TransactionModel $transactionModel
    ): void
    {
        if (Auth::id() !== $transactionModel->owner_id) {
            throw new UserIsNotTransactionOwnerException(
                Auth::id(), $transactionModel->id
            );
        }
    }
}
