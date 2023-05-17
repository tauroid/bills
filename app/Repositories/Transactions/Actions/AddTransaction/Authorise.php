<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

trait Authorise {
    /** @param Request $request
     *  @param RelevantData $generalRelevantData */

    protected static function getAuthorisationRelevantData(
        mixed $request,
        mixed $generalRelevantData
    ): mixed
    {
        $relevantData = new AuthorisationRelevantData;
        AuthorisationRelevantData
            ::fillFromSettlementAndNewParties(
                $relevantData,
                $request->settlement,
                $generalRelevantData->parties
            );
        return $relevantData;
    }

    /** @param AuthorisationRelevantData $relevantData */
    protected static function authoriseRequest(
        mixed $request, mixed $relevantData,
        mixed $_, mixed $__
    ): mixed
    {
        $relevantData->checkUserCanAddNewParties();

        return null;
    }
}
