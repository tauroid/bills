<?php

namespace App\Models;

use App\Models\Base\SettlementAdmin as BaseSettlementAdmin;

class SettlementAdmin extends BaseSettlementAdmin
{
	protected $fillable = [
		'settlement_id',
		'user_id'
	];
}
