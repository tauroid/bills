<?php

namespace App\ViewModels;

use App\Models\Entity;
use App\Models\Settlement as SettlementModel;
use App\Models\User;
use App\Repositories\Settlements as SettlementsRepository;
use App\Roles\Settlement as SettlementRoles;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Auth;

class Settlement implements Arrayable {
    private SettlementModel $settlement;

    function __construct(SettlementModel $settlement) {
        $this->settlement = $settlement;
    }

    function getOwnerData() {
        SettlementRoles::verifyUserIsOwner($this->settlement);

        $userDetails = fn ($user) => [
            'id' => $user->id,
            'name' => $user->name,
        ];

        return [
            'ownership_candidates' =>
            SettlementsRepository::potentialOwnershipAssignees(
                $this->settlement)->map($userDetails),
            'admin_candidates' =>
            SettlementsRepository::potentialAdminGrantees(
                $this->settlement)->map($userDetails),
            'admin_revocation_candidates' =>
            SettlementsRepository::potentialAdminRevokees(
                $this->settlement)->map($userDetails),
            'admins' => SettlementsRepository::admins(
                $this->settlement)->map(
                    fn ($admin) => [
                        'id' => $admin->user->id,
                        'name' => $admin->user->name,
                    ])
        ];
    }

    function toArray() {
        $statuses = $this->settlement->participant_statuses();

        $user = User::find(Auth::id());

        $result = [
            'id' => $this->settlement->id,
            'name' => $this->settlement->name,
            'user_id' => Auth::id(),
            'user_entity_id' => $user->entities[0]->id,
            'user_name' => $user->name,
            'full_authority' =>
            SettlementRoles::userIsOwner($this->settlement)
            || SettlementRoles::userIsAdmin($this->settlement),
            'linked_users_entities' =>
            $user->allLinkedUsers()->map(
                function ($linked_user) {
                    $entity = $linked_user->entities[0];
                    return [
                        'id' => $entity->id,
                        'name' => $entity->displayName()
                    ];
                }),
            'dummy_entities' => $user->dummy_entities->map(
                function ($dummy_entity) {
                    $entity = $dummy_entity->entity;
                    $associatedUserName =
                        $dummy_entity->real_entity
                        ? $dummy_entity->real_entity->names[0]->name
                        : null;

                    return [
                        'id' => $entity->id,
                        'name' => $entity->names[0]->name,
                        'associatedUserName' => $associatedUserName,
                        'displayName' => $entity->displayName()
                    ];
                }
            ),
            'statuses' => array_map(function ($entity_id, $status) {
                $entity = Entity::find($entity_id);
                return [
                    'id' => $entity->id,
                    'name' => $entity->displayName(),
                    'multiAmount' => $status
                ];
            }, array_keys($statuses), array_values($statuses)),
            'transactions' =>
            $this->settlement
            ->transactions()
            ->orderBy('created_at','desc')->get()->map(
                fn ($transaction) =>
                Transaction::toArray(
                    $this->settlement,
                    $transaction)),
        ];

        if (SettlementRoles::userIsOwner($this->settlement)) {
            $result['owner_data'] = $this->getOwnerData();
        }

        return $result;
    }
}
