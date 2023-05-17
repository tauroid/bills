<?php

namespace App\Models;

use App\Models\Base\AmountPercentage as BaseAmountPercentage;

class AmountPercentage extends BaseAmountPercentage
{
	protected $fillable = [
		'amount',
		'percentage'
	];
}
