<?php

namespace App\Models;

use App\Exceptions\AmountCurrencyMismatchException;
use App\Models\Base\Amount as BaseAmount;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Amount extends BaseAmount {
    const CURRENCY_AMOUNT_PROPERTY_NAMES = [
        'gbp' => 'amount_gbp',
        'percentage' => 'amount_percentage',
        'share' => 'amount_share'
    ];

    const CURRENCY_AMOUNT_CLASSES = [
        'gbp' => AmountGbp::class,
        'percentage' => AmountPercentage::class,
        'share' => AmountShare::class,
    ];

    protected $fillable = [
        'currency',
        'amount_gbp',
        'amount_percentage',
        'amount_share',
    ];

    public static function instantiateComplete(
        array $amountData
    ): self
    {
        $currency = $amountData['currency'];
        $currencyAmountPropertyName =
            self::CURRENCY_AMOUNT_PROPERTY_NAMES[$currency];
        $currencyAmountData =
            $amountData[$currencyAmountPropertyName];
        $currencyAmountClass =
            self::CURRENCY_AMOUNT_CLASSES[$currency];
        $amount = new Amount(['currency' => $currency]);
        $amount_currency = new $currencyAmountClass(
            $currencyAmountData
        );
        $amount->setRelation(
            $currencyAmountPropertyName,
            $amount_currency
        );
        return $amount;
    }

    public function saveComplete(): void
    {
        $this->save();
        $this->currency_amount->amount = $this->id;
        $this->currency_amount->save();
    }

    public static function createFromData(
        array $amountData
    ): Amount
    {
        $currency = $amountData['currency'];
        $currencyAmountPropertyName =
            self::CURRENCY_AMOUNT_PROPERTY_NAMES[$currency];
        $currencyAmountData =
            $amountData[$currencyAmountPropertyName];
        $currencyAmountClass =
            self::CURRENCY_AMOUNT_CLASSES[$currency];
        $amount = new Amount(['currency' => $currency]);
        $amount->save();
        $amount_currency =
            new $currencyAmountClass(
                array_merge(
                    $currencyAmountData,
                    ['amount' => $amount->id]));
        $amount_currency->save();
        return $amount;
    }

    public function getCurrencyAmountAttribute() {
        return $this->{$this->currencyAmountPropertyName()};
    }

    public function currencyAmountPropertyName() {
        return self::CURRENCY_AMOUNT_PROPERTY_NAMES[
            $this->currency];
    }

    private function assertSameCurrency(Amount $amount): void {
        if ($this->currency !== $amount->currency) {
            throw new AmountCurrencyMismatchException(
                $this->currency, $amount->currency);
        }
    }

    public function add(Amount $amount): Amount {
        if ($this->currency === 'zero') return $amount;

        $this->assertSameCurrency($amount);

        $thatCurrencyAmount =
            $amount->{$this->currencyAmountPropertyName()};

        return new Amount([
            'currency' => $this->currency,
            $this->currencyAmountPropertyName() =>
            $this->currency_amount->add($thatCurrencyAmount)
        ]);
    }

    public function subtract(Amount $amount): Amount {
        if ($this->currency === 'zero') return $amount->negative();

        $this->assertSameCurrency($amount);

        $thatCurrencyAmount =
            $amount->{$this->currencyAmountPropertyName()};

        return new Amount([
            'currency' => $this->currency,
            $this->currencyAmountPropertyName() =>
            $this->currency_amount->subtract($thatCurrencyAmount)
        ]);
    }

    public function negative(): Amount {
        if ($this->currency === 'zero') return $this;

        return new Amount([
            'currency' => $this->currency,
            $this->currencyAmountPropertyName() =>
            $this->currency_amount->negative()
        ]);
    }

    public function isNegative(): bool {
        if ($this->currency === 'zero') return false;

        return $this->currency_amount->isNegative();
    }

    public function equals(Amount $amount): bool {
        $this->assertSameCurrency($amount);

        $thatCurrencyAmount =
            $amount->{$this->currencyAmountPropertyName()};

        return $this->currency_amount->equals($thatCurrencyAmount);
    }

    public function multiply(float $scalar): Amount {
        return new Amount([
            'currency' => $this->currency,
            $this->currencyAmountPropertyName() =>
            $this->currency_amount->multiply($scalar)
        ]);
    }

    public static function zero(string $currency = 'zero'): Amount {
        if ($currency === 'zero') {
            return new Amount(['currency' => 'zero']);
        } else {
            return new Amount([
                'currency' => $currency,
                self::CURRENCY_AMOUNT_PROPERTY_NAMES[$currency] =>
                call_user_func([
                    self::CURRENCY_AMOUNT_CLASSES[$currency],
                    'zero'
                ])
            ]);
        }
    }

    public function isZero(): bool {
        if ($this->currency === 'zero') return true;
        return $this->equals(Amount::zero($this->currency));
    }
}
