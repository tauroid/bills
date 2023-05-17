<?php

namespace App\Tokens;

use App\Exceptions\TokenRequiresArgumentsException;
use App\Models\Settlement;
use App\Repositories\SettlementPermissions;

class UserOwnsSettlement extends Token {
    private Settlement $settlement;

    private function __construct(Settlement $settlement) {
        $this->settlement = $settlement;
    }

    public function getSettlement(): Settlement {
        return $this->settlement;
    }

    public static function withToken(
        callable $continuation,
        Settlement $settlement = null)
    {
        if (is_null($settlement)) {
            throw new TokenRequiresArgumentsException(
                ['settlement' => Settlement::class]);
        }

        SettlementPermissions::verifyUserIsOwner($settlement);

        $continuation(new self($settlement));
    }
}
