<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

use App\Models\Entity;
use App\Models\Settlement;
use App\Models\User;
use App\Repositories\Transactions\AttributedPartiesNotKnownToUserException;
use App\Repositories\Transactions\UnauthorisedNewPartiesException;
use App\Repositories\Transactions\UserIsNotInvolvedWithSettlementException;
use App\Roles\Settlement as SettlementRoles;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AuthorisationRelevantData {
    public Settlement $settlement;

    public bool $userIsOwner;
    public bool $userIsAdmin;
    public bool $userIsParticipant;

    // not relevant if the user is not a participant
    public Collection $partiesNewToSettlement;
    // not relevant if the user is not an owner or admin
    public ?Collection $newPartiesNotKnownToUser = null;

    private static function getPartiesNewToSettlement(
        Settlement $settlement, Collection $parties
    ): Collection
    {
        $participantIds =
            $settlement->participantEntities()->map(
                fn ($entity) => $entity->id
            );

        return $parties->filter(fn ($id) =>
            !$participantIds->contains($id)
        )->unique();
    }

    private static function getPartiesNotKnownToUser(
        Collection $parties
    ): Collection
    {
        return $parties->filter(function ($id) {
            $entity = Entity::find($id);
            return !$entity ||
                !User::find(Auth::id())->knowsEntity($entity);
        });
    }

    static function fillFromSettlementAndNewParties(
        self $relevantData,
        Settlement $settlement,
        Collection $newParties
    ): void
    {
        $relevantData->settlement = $settlement;

        $relevantData->userIsOwner =
            SettlementRoles::userIsOwner($settlement);
        $relevantData->userIsAdmin =
            SettlementRoles::userIsAdmin($settlement);
        $relevantData->userIsParticipant =
            SettlementRoles::userIsParticipant($settlement);

        $relevantData->partiesNewToSettlement =
            self::getPartiesNewToSettlement(
                $settlement,
                $newParties
            );

        if ($relevantData->userIsOwner
            || $relevantData->userIsAdmin)
        {
            $relevantData->newPartiesNotKnownToUser =
                self::getPartiesNotKnownToUser(
                    $relevantData->partiesNewToSettlement
                );
        }
    }

    function checkUserCanAddNewParties(): void
    {
        if ($this->userIsOwner
            || $this->userIsAdmin)
        {
            if (!$this->newPartiesNotKnownToUser
                ->isEmpty())
            {
                throw new AttributedPartiesNotKnownToUserException(
                    $this->settlement,
                    $this->newPartiesNotKnownToUser->toArray()
                );
            }
        } elseif ($this->userIsParticipant) {
            if (!$this->partiesNewToSettlement
                ->isEmpty()) {
                throw new UnauthorisedNewPartiesException(
                    $this->settlement,
                    $this->partiesNewToSettlement->toArray()
                );
            }
        } else {
            throw new UserIsNotInvolvedWithSettlementException(
                $this->settlement
            );
        }
    }
}
