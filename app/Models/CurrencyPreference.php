<?php

namespace App\Models;

use App\Models\Base\CurrencyPreference as BaseCurrencyPreference;

class CurrencyPreference extends BaseCurrencyPreference
{
	protected $fillable = [
		'user_id',
		'currency'
	];
}
