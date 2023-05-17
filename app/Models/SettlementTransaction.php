<?php

namespace App\Models;

use App\Models\Base\SettlementTransaction as BaseSettlementTransaction;

class SettlementTransaction extends BaseSettlementTransaction
{
	protected $fillable = [
		'settlement_id',
		'transaction_id'
	];
}
