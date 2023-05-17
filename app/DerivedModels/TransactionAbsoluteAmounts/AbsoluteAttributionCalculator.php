<?php

namespace App\DerivedModels\TransactionAbsoluteAmounts;

use App\Models\Amount;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use Illuminate\Support\Collection;

class AbsoluteAttributionCalculator {
    private Amount $amount;
    private Collection $mixedAttributions;
    private Amount $remainder;
    private int $shares;
    private Collection $absoluteNonShareAttributions;

    private function __construct(
        Amount $amount,
        Collection $mixedAttributions,
    ) {
        $this->amount = $amount;
        $this->mixedAttributions = $mixedAttributions;
        $this->remainder = $amount;
        $this->shares = 0;
        $this->absoluteNonShareAttributions = new Collection;

        foreach ($this->mixedAttributions as $attribution) {
            $this->accumulate($attribution);
        }
    }

    static function calculateAbsoluteAttributions(
        mixed $transactionAmount,
        Collection $mixedAttributions
    ): Collection
    {
        $calculator = new self(
            $transactionAmount,
            $mixedAttributions
        );

        return $calculator->extractAbsoluteAttributions();
    }

    private function accumulate(
        TransactionFrom|TransactionTo $attribution
    ): void
    {
        if ($attribution->amount->currency === 'share') {
            $this->shares +=
                $attribution->amount->amount_share->share;

            $this->absoluteNonShareAttributions []= null;
        } else {
            $absoluteAmount =
                $attribution->amount->currency === 'percentage'
                ? $this->amount->multiply(
                        $attribution->amount
                        ->amount_percentage->percentage
                        /100)
                : $attribution->amount;

            $this->absoluteNonShareAttributions->push(
                new Attribution(
                    $attribution->entity,
                    $absoluteAmount
                )
            );

            $this->remainder =
                $this->remainder->subtract($absoluteAmount);
        }
    }

    private function extractAbsoluteAttributions(): Collection
    {
        return $this->mixedAttributions->zip(
            collect($this->absoluteNonShareAttributions)
        )->map(function ($pair) {
            [$attribution, $absoluteAttribution] = $pair;

            if ($attribution->amount->currency === 'share') {
                $share = $attribution->amount
                         ->currency_amount->share;

                $amount = null;
                if ($share === $this->shares) {
                    $amount = $this->remainder;
                    $this->remainder = Amount::zero();
                    $this->shares = 0;
                } else {
                    $amount = $this->remainder
                        ->multiply($share/$this->shares);
                    $this->remainder =
                        $this->remainder->subtract($amount);
                    $this->shares -= $share;
                }

                return new Attribution(
                    $attribution->entity,
                    $amount
                );
            } else {
                return $absoluteAttribution;
            }
        });

    }
}
