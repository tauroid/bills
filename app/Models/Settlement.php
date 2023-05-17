<?php

namespace App\Models;

use App\DerivedModels\MultiCurrencyAmount;
use App\DerivedModels\TransactionAbsoluteAmounts;
use App\Exceptions\AmountCurrencyMismatchException;
use App\Exceptions\OutstandingMultipleCurrenciesException;
use App\Models\Base\Settlement as BaseSettlement;
use Illuminate\Support\Collection;

class Settlement extends BaseSettlement {
    protected $fillable = [
        'name'
    ];

    public function owner() {
        return $this->user();
    }

    // Outstanding doesn't really make sense for multiple
    // currencies because someone can be down in one and
    // up in the other and exchange rates aren't constant
    //
    // Could deal with an exchange rate API down the road
    // but for now if there's more than one currency (or none)
    // we'll just throw an exception
    public function outstanding(callable $transactionFilter = null): Amount {
        $amount = Amount::zero();

        foreach ($this->participant_statuses($transactionFilter) as $status) {
            if (count($status->currencies()) > 1) {
                throw new OutstandingMultipleCurrenciesException(
                    $status->currencies());
            }

            $status = array_values($status->amounts())[0];

            if (!$status->isNegative()) continue;

            if (is_null($amount)) {
                $amount = $status->negative();
            } else {
                try {
                    $amount = $amount->add($status->negative());
                } catch (AmountCurrencyMismatchException) {
                    throw new OutstandingMultipleCurrenciesException(
                        [$amount->currency, $status->currency]);
                }
            }
        }

        return $amount;
    }

    public function outlay(): ?Amount {
        return $this->outstanding(fn($t)=>$t->outlay);
    }

    public function participantEntities(): Collection {
        return $this->transactions->flatMap(
            fn ($transaction) =>
            $transaction->froms->map(
                fn ($from) => $from->entity
            )->merge($transaction->tos->map(
                fn ($to) => $to->entity
            ))
        )->unique('id')->values();
    }

    public function resolvedParticipantEntities(): Collection {
        return $this->participantEntities()->map(
            fn ($entity) => $entity->resolvedEntity()
        )->unique('id')->values();
    }

    public function participants(): Collection {
        return $this->resolvedParticipantEntities(
        )->map(fn ($entity) => [
            'id' => $entity->id,
            'name' => $entity->displayName()
        ]);
    }

    public function participantUsers(): Collection {
        return $this->resolvedParticipantEntities()
            ->filter(fn ($entity) =>
                     $entity->users->isNotEmpty())
            ->map(fn ($entity) => $entity->users[0]);
    }

    public function participant_statuses(
        callable $transactionFilter = null): array
    {
        return $this->transactions->reduce(
            function ($statuses, $transaction) use ($transactionFilter) {
                if (!is_null($transactionFilter)
                    && !$transactionFilter($transaction))
                {
                    return $statuses;
                }

                $transactionAbsoluteAmounts =
                    new TransactionAbsoluteAmounts($transaction);

                $currency = $transactionAbsoluteAmounts
                          ->getAmount()->currency;

                foreach ($transactionAbsoluteAmounts->getFroms()
                         as $from) {
                    $entity = $from->getEntity();
                    $realEntity = $entity->realEntity();
                    if ($realEntity) $entity = $realEntity;
                    $entity_id = $entity->id;
                    $amount = $from->getAmount();
                    if (!array_key_exists(
                        $entity_id, $statuses))
                    {
                        $statuses[$entity_id] =
                            new MultiCurrencyAmount([
                                $currency => $amount->negative()
                            ]);
                    } else {
                        $statuses[$entity_id] =
                            $statuses[$entity_id]
                            ->addAmount($amount->negative());
                    }
                }

                foreach ($transactionAbsoluteAmounts->getTos()
                         as $to) {
                    $entity = $to->getEntity();
                    $realEntity = $entity->realEntity();
                    if ($realEntity) $entity = $realEntity;
                    $entity_id = $entity->id;
                    $amount = $to->getAmount();
                    if (!array_key_exists(
                        $entity_id, $statuses))
                    {
                        $statuses[$entity_id] =
                            new MultiCurrencyAmount([
                                $currency => $amount
                            ]);
                    } else {
                        $statuses[$entity_id] =
                            $statuses[$entity_id]
                            ->addAmount($amount);
                    }
                }

                return $statuses;
            }, []);
    }

    public function hasParticipant(int $id): bool {
        return $this->settlement_participants
            ->where('entity_id', $id)->isNotEmpty();
    }
}
