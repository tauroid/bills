<?php

namespace App\Models;

use App\Models\Base\TransactionTo as BaseTransactionTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionTo extends BaseTransactionTo
{
	use SoftDeletes;

	protected $fillable = [
		'entity_id',
		'amount_id',
		'transaction'
	];
}
