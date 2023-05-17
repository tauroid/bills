<?php

namespace App;

use App\Exceptions\OutstandingMultipleCurrenciesException;
use App\Models\Entity;
use App\Models\LinkingUri;
use App\Models\User;
use App\Roles\Settlement as SettlementRoles;
use App\ViewModels\Settlement as SettlementViewModel;
use Illuminate\Support\Facades\Auth;

class CommonData {
    public static function get(): array {
        $user = User::find(Auth::id());
        $linkingUri = LinkingUri::getLinkingUriForAuthedUser();
        return [
            'user_id' => Auth::id(),
            'user_name' => $user->name,
            'user_entity_id' => $user->entities[0]->id,
            'dummyEntities' => array_map(function ($dummyEntity) {
                $associatedUserName
                    = $dummyEntity->real_entity
                    ? $dummyEntity->real_entity->names[0]->name
                    : null;
                return [
                    'name' => $dummyEntity->entity->names[0]->name,
                    'id' => $dummyEntity->id,
                    'displayName' => $dummyEntity->entity->displayName(),
                    'associatedUserName' => $associatedUserName
                ];
            }, $user->dummy_entities->all()),
            'linkedUsers' => $user->allLinkedUsers()->map(
                function ($linkedUser) {
                    return [
                        'name' => $linkedUser->name,
                        'id' => $linkedUser->id,
                        'entityId' => $linkedUser->entities[0]->id
                    ];
                })->toArray(),
            'linkingUri' =>
            $linkingUri ? '/link/'.$linkingUri->uri : null,
            'settlements' =>
            $user->entities[0]->settlements(
            )->merge($user->settlements)->merge(
                $user->settlement_admins->map(
                    fn($admin) => $admin->settlement
                )->filter(fn($settlement)=>!is_null($settlement))
            )->unique('id')->sortByDesc('created_at')->values(
            )->map(function ($settlement) {
                try {
                    $outstanding = $settlement->outstanding();
                    if ($outstanding->isZero()) {
                        $outstanding = null;
                    }
                    $outlay = $settlement->outlay();
                    if ($outlay->isZero()) {
                        $outlay = null;
                    }
                    $multiple_currencies = false;
                } catch (OutstandingMultipleCurrenciesException) {
                    $outstanding = null;
                    $outlay = null;
                    $multiple_currencies = true;
                }

                $statuses = $settlement->participant_statuses();

                $result = [
                    'id' => $settlement->id,
                    'name' => $settlement->name,
                    'created_at' => $settlement->created_at,
                    'outstanding' => $outstanding,
                    'participants' => $settlement->participants(),
                    'statuses' =>
                    array_map(function ($entity_id, $status) {
                        $entity = Entity::find($entity_id);
                        return [
                            'id' => $entity->id,
                            'name' => $entity->displayName(),
                            'multiAmount' => $status
                        ];
                    }, array_keys($statuses),
                        array_values($statuses)),
                    'inconsistent_transactions' =>
                    $settlement->transactions->some(
                        fn($tr)=>$tr->isInconsistent()),
                    'multiple_currencies' => $multiple_currencies,
                    'authority' =>
                    SettlementRoles::userIsOwner($settlement)
                    || SettlementRoles::userIsAdmin($settlement)
                ];

                if (SettlementRoles::userIsOwner($settlement)) {
                    $result['owner_data'] =
                        (new SettlementViewModel($settlement))
                        ->getOwnerData();
                }

                return $result;
            })
        ];
    }
}
