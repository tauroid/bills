<?php

namespace App\Models;

use App\Models\Base\AmountUsd as BaseAmountUsd;

class AmountUsd extends BaseAmountUsd
{
	protected $fillable = [
		'dollars',
		'cents',
		'amount'
	];
}
