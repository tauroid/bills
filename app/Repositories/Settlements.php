<?php

namespace App\Repositories;

use App\Exceptions\InvalidAssignedSettlementOwner;
use App\Models\Settlement;
use App\Models\User;
use App\Repositories\Settlements\UserCantViewSettlementException;
use App\Roles\Settlement as SettlementRoles;
use App\ViewModels\Settlement as SettlementViewModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * class Settlements
 * @method static void changeOwner(Settlement $settlement, User $newOwner)
 * @method static Collection potentialOwnershipAssignees(Settlement $settlement)
 * @method static Collection potentialAdminGrantees(Settlement $settlement)
 * @method static Collection potentialAdminRevokees(Settlement $settlement)
 * @method static Collection admins(Settlement $settlement)
 * @method static void addAdmin(Settlement $settlement, User $user)
 * @method static void removeAdmin(Settlement $settlement, User $user)
 * @method static void viewSettlement(Settlement $settlement)
 * @method static void addSettlement(array $attributes)
 * @method static void editSettlement(Settlement $settlement, array $attributes)
 */
class Settlements {
    static function viewSettlement(
        Settlement $settlement
    ): Response
    {
        if (!SettlementRoles::userIsOwner($settlement)
            && !SettlementRoles::userIsAdmin($settlement)
            && !SettlementRoles::userIsParticipant($settlement))
        {
            throw new UserCantViewSettlementException(
                $settlement
            );
        }
        return Inertia::render(
            'Settlement', new SettlementViewModel($settlement));
    }

    static function addSettlement(array $attributes): void {
        DB::transaction(function () use ($attributes) {
            $settlement = new Settlement($attributes);
            $settlement->owner_id = Auth::id();
            $settlement->save();
            $settlement->settlement_admins()->create([
                'user_id' => Auth::id()
            ]);
        });
    }

    static function __callStatic($name, $args): mixed {
        SettlementRoles::verifyUserIsOwner($args[0]);
        return SettlementsGuardedPrivateDoNotImport::$name(...$args);
    }
}

class SettlementsGuardedPrivateDoNotImport {
     static function changeOwner(Settlement $settlement, User $newOwner): void {
        if (!self::potentialOwnershipAssignees($settlement)
            ->some(fn ($user) => $user->id === $newOwner->id))
        {
            throw new InvalidAssignedSettlementOwner(
                User::find(Auth::id()), $newOwner);
        }

        $settlement->owner_id = $newOwner->id;
        $settlement->save();
    }

    static function potentialOwnershipAssignees(
        Settlement $settlement
    ): Collection {
        return User::find(Auth::id())->allLinkedUsers()->merge(
            $settlement->settlement_participants
            ->filter(fn ($participant) =>
                     $participant->entity->users->isNotEmpty())
            ->map(fn ($participant) => $participant->entity->users[0])
        );
    }

    static function potentialAdminGrantees(
        Settlement $settlement
    ): Collection {
        return self::potentialOwnershipAssignees($settlement)
            ->diff(self::admins($settlement)->map(
                fn ($admin) => $admin->user
            ));
    }

    static function potentialAdminRevokees(
        Settlement $settlement
    ): Collection {
        return $settlement->settlement_admins->map(
            fn ($admin) => $admin->user
        );
    }

    static function admins(Settlement $settlement): Collection {
        return $settlement->settlement_admins;
    }

    static function addAdmin(
        Settlement $settlement,
        User $user
    ): void {
        if ($settlement->settlement_admins->some(
            fn ($admin) => $admin->user_id === $user->id))
        {
            return;
        }

        $settlement->settlement_admins()->create([
            'user_id' => $user->id
        ]);
    }

    static function removeAdmin(
        Settlement $settlement,
        User $user
    ): void {
        $admin = $settlement->settlement_admins
               ->firstWhere('user_id', $user->id);
        if (is_null($admin)) return;
        $admin->delete();
    }

    static function editSettlement(
        Settlement $settlement,
        array $attributes
    ): void {
        $settlement->update($attributes);
    }

    static function deleteSettlement(
        Settlement $settlement
    ): void {
        $settlement->delete();
    }
}
