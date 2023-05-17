<?php

namespace App\Models;

use App\Models\Base\AmountGbp as BaseAmountGbp;

class AmountGbp extends BaseAmountGbp {
    protected $fillable = [
        'amount',
        'pounds',
        'pence'
    ];

    public function add(AmountGbp $amountGbp): AmountGbp {
        $totalInPence = ($this->pounds+$amountGbp->pounds)*100
               + $this->pence + $amountGbp->pence;

        return new self([
            'pounds' => intdiv($totalInPence, 100),
            'pence' => $totalInPence % 100,
        ]);
    }

    public function subtract(AmountGbp $amountGbp): AmountGbp {
        return $this->add($amountGbp->negative());
    }

    public function negative(): AmountGbp {
        return new AmountGbp([
            'pounds' => -$this->pounds,
            'pence' => -$this->pence,
        ]);
    }

    public function isNegative(): bool {
        return $this->pounds < 0 || $this->pence < 0;
    }

    public function equals(AmountGbp $amountGbp): bool {
        return $this->pounds === $amountGbp->pounds
            && $this->pence === $amountGbp->pence;
    }

    public function multiply(float $scalar): AmountGbp {
        $pounds = $this->pounds * $scalar;
        $pence = $this->pence * $scalar;

        $totalInPence = round($pounds*100 + $pence);

        return new self([
            'pounds' => intdiv($totalInPence, 100),
            'pence' => $totalInPence % 100,
        ]);
    }

    public static function zero(): AmountGbp {
        return new AmountGbp([
            'pounds' => 0,
            'pence' => 0,
        ]);
    }
}
