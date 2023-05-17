<?php

namespace App\Models;

use App\Models\Base\TransactionFrom as BaseTransactionFrom;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFrom extends BaseTransactionFrom
{
	use SoftDeletes;

	protected $fillable = [
		'entity_id',
		'amount_id',
		'transaction'
	];
}
