<?php

namespace App\Repositories\Transactions\Actions\AddTransaction;

use App\Models\Transaction;
use App\Repositories\Transactions\DescriptionIsntStringException;
use App\Repositories\Transactions\DescriptionTooLongException;
use App\Repositories\Transactions\MissingAttributionsException;
use App\Repositories\Transactions\Validation\Limits;

/*
 * Validate AddTransaction arguments
 *
 * * Every party has to already exist in the database
 * * The transaction amount has to be an absolute currency
 * * All absolute currencies have to be the same
 * * All of 'froms', 'tos', 'type' and 'amount' have to be provided
 */
trait Validate {

    private static function checkAttributionCategories(
        array $transactionData
    ): void
    {
        $missingAttributionCategories = [];
        if (!array_key_exists('froms', $transactionData)
            || empty($transactionData['froms'])
        ) {
            $missingAttributionCategories []= 'from';
        }
        if (!array_key_exists('tos', $transactionData)
            || empty($transactionData['tos'])
        ) {
            $missingAttributionCategories []= 'to';
        }
        if (!empty($missingAttributionCategories)) {
            throw new MissingAttributionsException(
                $missingAttributionCategories
            );
        }
    }

    /** @param Request $request
     *  @param ValidationRelevantData $relevantData */
    protected static function validateRequest(
        mixed $request, mixed $_, mixed $__
    ): mixed
    {
        self::checkAttributionCategories(
            $request->transactionData);

        if (!array_key_exists('amount', $request->transactionData)
            || !$request->transactionData['amount'])
        {
            throw new MissingTransactionAmountException;
        }

        if (!array_key_exists('type', $request->transactionData)
            || !$request->transactionData['type']) {
            throw new MissingTransactionTypeException;
        }

        if (array_key_exists(
            'description', $request->transactionData) &&
            !is_null($request->transactionData['description']))
        {
            $description =
                $request->transactionData['description'];
            if (!is_string($description))
            {
                throw new DescriptionIsntStringException(
                    $description);
            }
            if (strlen($description)
                > Limits::DESCRIPTION_LENGTH)
            {
                throw new DescriptionTooLongException(
                    $description
                );
            }
        }

        return null;
    }

    /** @param Transaction $transaction */
    protected static function validateChanges(
        array $toBeSaved, array $_
    ): mixed
    {
        [$transaction] = $toBeSaved;
        $transaction->validate();
        return null;
    }
}
