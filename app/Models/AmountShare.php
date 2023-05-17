<?php

namespace App\Models;

use App\Models\Base\AmountShare as BaseAmountShare;

class AmountShare extends BaseAmountShare
{
	protected $fillable = [
		'amount',
		'share'
	];

	public function equals(AmountShare $amount): bool {
		return $amount->share === $this->share;
	}
}
