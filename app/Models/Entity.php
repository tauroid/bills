<?php

namespace App\Models;

use App\Models\Base\Entity as BaseEntity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class Entity extends BaseEntity {
    function correspondsToUser(Authenticatable $user) {
        if (!$this->users->isEmpty() &&
            $user->id === $this->users[0]->id)
        {return true;}

        if (!is_null($this->dummy_entity)) {
            $real = $this->dummy_entity->real_entity;
            if (is_null($real)) return false;
            if (!$real->users->isEmpty() &&
                $user->id === $real->users[0]->id)
            {
                return true;
            }
        }

        return false;
    }

    function settlements() {
        $settlement_ids = new Collection;
        foreach ($this->transaction_froms as $from) {
            if ($from->transaction?->settlements->isNotEmpty()) {
                $settlement = $from->transaction->settlements[0];
                $settlement_ids->push($settlement);
            }
        }
        foreach ($this->transaction_tos as $to) {
            if ($to->transaction?->settlements->isNotEmpty()) {
                $settlement = $to->transaction->settlements[0];
                $settlement_ids->push($settlement);
            }
        }
        return $settlement_ids;
    }

    function resolvedEntity(): Entity {
        $realEntity = $this->realEntity();
        if ($realEntity) return $realEntity;
        return $this;
    }

    function realEntity(): ?Entity {
        return $this->dummy_entity?->real_entity;
    }

    function displayName(): string {
        $entityName = $this->names[0]->name;

        $realEntity = $this->realEntity();

        $realEntityName =
            $realEntity
            ? $realEntity->names[0]->name
            : null;

        return is_null($realEntityName)
            ? $entityName : (
                $realEntityName === $entityName
                ? $realEntityName . ' (linked placeholder)'
                : $entityName . ' ('.$realEntityName.')'
            );
    }
}
