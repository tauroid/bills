<?php

namespace App\DerivedModels;

use App\Models\Amount;
use Illuminate\Contracts\Support\Arrayable;

class MultiCurrencyAmount implements Arrayable {
    private array $amounts = [];

    function __construct(array $amounts) {
        $this->amounts = $amounts;
    }

    function currencies() {
        return array_keys($this->amounts);
    }

    function amounts() {
        return $this->amounts;
    }

    public function add(MultiCurrencyAmount $multiCurrencyAmount) {
        $newAmounts = $this->amounts;

        foreach ($multiCurrencyAmount->amounts() as $currency => $amount) {
            if (!array_key_exists($currency, $newAmounts)) {
                $newAmounts[$currency] = $amount;
            } else {
                $newAmounts[$currency] = $newAmounts[$currency]->add($amount);
            }
        }

        return new MultiCurrencyAmount($newAmounts);
    }

    public function addAmount(Amount $amount) {
        return $this->add(new MultiCurrencyAmount([$amount->currency => $amount]));
    }

    public static function zero(): MultiCurrencyAmount {
        return new MultiCurrencyAmount([]);
    }

    public function toArray() {
        return array_map(fn ($amount) => [
            'currency' => $amount->currency,
            $amount->currencyAmountPropertyName() =>
            $amount->currency_amount
        ], $this->amounts);
    }
}
