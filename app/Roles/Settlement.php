<?php

namespace App\Roles;

use App\Attributes\ThrowsWhenAssertionFails;
use App\Models\Settlement as SettlementModel;
use App\Models\SettlementParticipant;
use App\Models\User;
use App\Roles\Settlement\UserIsNotSettlementOwnerException;
use App\Roles\Settlement\UserIsNotSettlementAdminException;
use App\Roles\Settlement\UserIsNotSettlementParticipantException;
use Illuminate\Support\Facades\Auth;

/**
 * class Settlement
 * @method static bool userIsOwner(SettlementModel $settlementModel)
 * @method static bool userIsAdmin(SettlementModel $settlementModel)
 * @method static bool userIsParticipant(SettlementModel $settlementModel)
 */
class Settlement extends Roles {

    #[ThrowsWhenAssertionFails(
        UserIsNotSettlementOwnerException::class)]
    static function verifyUserIsOwner(
        SettlementModel $settlementModel
    ): void
    {
        if (Auth::id() !== $settlementModel->owner->id) {
            throw new UserIsNotSettlementOwnerException(
                Auth::id(), $settlementModel->id
            );
        }
    }

    #[ThrowsWhenAssertionFails(
        UserIsNotSettlementAdminException::class)]
    static function verifyUserIsAdmin(
        SettlementModel $settlementModel
    ): void
    {
        if (!$settlementModel->settlement_admins->some(
            fn($admin)=>$admin->user_id === Auth::id())
        ) {
            throw new UserIsNotSettlementAdminException(
                Auth::id(), $settlementModel->id
            );
        }
    }

    #[ThrowsWhenAssertionFails(
        UserIsNotSettlementParticipantException::class)]
    static function verifyUserIsParticipant(
        SettlementModel $settlementModel
    ): void
    {
        if (!$settlementModel->participantUsers()->some(
            fn ($user) => $user->id === Auth::id()
        )) {
            throw new UserIsNotSettlementParticipantException(
                $settlementModel, User::find(Auth::id())
            );
        }
    }
}
