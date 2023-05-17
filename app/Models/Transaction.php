<?php

namespace App\Models;

use App\DerivedModels\TransactionAbsoluteAmounts;
use App\Library\Currency;
use App\Models\Base\Transaction as BaseTransaction;
use App\Repositories\Transactions\Actions\AddTransaction\MultipleAbsoluteCurrenciesException;
use App\Repositories\Transactions\Actions\AddTransaction\PartiesDoNotExistException;
use App\Repositories\Transactions\Actions\RelativeTransactionCurrencyException;
use App\Repositories\Transactions\MissingAttributionsException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Transaction extends BaseTransaction {
    use SoftDeletes;

    protected $fillable = ['type','amount_id','owner_id'];

    function settlement_transactions() {
        return $this->hasMany(SettlementTransaction::class);
    }

    function froms() { return $this->transaction_froms(); }
    function tos() { return $this->transaction_tos(); }

    function isInconsistent(): bool {
        $absolute = new TransactionAbsoluteAmounts($this);

        $fromTotal = $absolute->getFroms()->reduce(
            fn($total, $from) => $total->add($from->getAmount()),
            Amount::zero());

        $toTotal = $absolute->getTos()->reduce(
            fn($total, $to) => $total->add($to->getAmount()),
            Amount::zero());

        return !$fromTotal->equals($toTotal)
            || !$toTotal->equals($this->amount);
    }

    /** @return Collection<string> */
    function getAbsoluteCurrencies(): Collection {
        $attributionCurrency = fn ($attribution) =>
            $attribution->amount->currency;

        return
            $this->froms->map($attributionCurrency)
            ->merge($this->tos->map($attributionCurrency))
            ->merge(collect([$this->amount->currency]))
            ->filter([Currency::class, 'currencyIsAbsolute'])
            ->unique();
    }

    function getNonExistentPartyIds(): Collection {
        return
            $this->froms->map(fn ($from) => $from->entity_id)
            ->merge($this->tos->map(fn ($to) => $to->entity_id))
            ->filter(fn ($id) => Entity::find($id) === null);
    }

    function validate(): void {
        $missingAttributionCategories = [];
        if ($this->froms->isEmpty()) {
            $missingAttributionCategories []= 'from';
        }
        if ($this->tos->isEmpty()) {
            $missingAttributionCategories []= 'to';
        }
        if (!empty($missingAttributionCategories)) {
            throw new MissingAttributionsException(
                $missingAttributionCategories
            );
        }

        if (!Currency::currencyIsAbsolute(
            $this->amount->currency))
        {
            throw new RelativeTransactionCurrencyException(
                $this->amount->currency
            );
        }

        $absoluteCurrencies = $this->getAbsoluteCurrencies();
        if (count($absoluteCurrencies) > 1)
        {
            throw new MultipleAbsoluteCurrenciesException(
                $absoluteCurrencies->toArray()
            );
        }

        $nonExistentPartyIds = $this->getNonExistentPartyIds();
        if ($nonExistentPartyIds->count() > 0) {
            throw new PartiesDoNotExistException(
                $nonExistentPartyIds->toArray()
            );
        }
    }

    function saveComplete(): void {
        $this->amount->save();
        $this->amount_id = $this->amount->id;
        $this->save();
        $this->settlement_transactions->each(function ($link) {
            $link->transaction_id = $this->id;
            $link->save();
        });
        $this->amount->saveComplete();
        $saveAttribution = function (
            TransactionFrom|TransactionTo $attribution)
        {
            $attribution->transaction_id = $this->id;
            $attribution->amount->saveComplete();
            $attribution->amount_id = $attribution->amount->id;
            $attribution->save();
        };
        $this->froms->each($saveAttribution);
        $this->tos->each($saveAttribution);
    }
}
