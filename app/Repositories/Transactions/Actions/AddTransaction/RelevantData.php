<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

use App\Models\Entity;
use Illuminate\Support\Collection;

class RelevantData {
    /** @var Collection<Entity|array> */
    public Collection $parties;
}
