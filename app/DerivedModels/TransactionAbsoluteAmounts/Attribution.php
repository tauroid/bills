<?php

namespace App\DerivedModels\TransactionAbsoluteAmounts;

use App\Models\Amount;
use App\Models\Entity;

class Attribution {
    private Entity $entity;
    private Amount $amount;

    function __construct(
        Entity $entity, Amount $amount)
    {
        $this->entity = $entity;
        $this->amount = $amount;
    }

    function getEntity(): Entity {
        return $this->entity;
    }

    function getAmount(): mixed {
        return $this->amount;
    }
}
