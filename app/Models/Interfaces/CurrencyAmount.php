<?php

namespace App\Models\Interfaces;

// DEPRECATED
// it just doesn't work like that (self is not late binding)
interface CurrencyAmount {
    function add(self $amount): self;
    function subtract(self $amount): self;
    function negative(): self;
    function isNegative(): bool;
    function equals(self $amount): bool;
    // (the closest value)
    function multiply(float $scalar): self;
    function zero(): self;
}
