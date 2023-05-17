<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use App\Models\Settlement;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionAllocation
 * 
 * @property int $id
 * @property int $transaction
 * @property int|null $settlement
 * @property int $amount
 * 
 *
 * @package App\Models\Base
 */
class TransactionAllocation extends Model
{
	protected $table = 'transaction_allocation';
	public $timestamps = false;

	protected $casts = [
		'transaction' => 'int',
		'settlement' => 'int',
		'amount' => 'int'
	];

	public function transaction()
	{
		return $this->belongsTo(Transaction::class, 'transaction');
	}

	public function settlement()
	{
		return $this->belongsTo(Settlement::class, 'settlement');
	}

	public function amount()
	{
		return $this->belongsTo(Amount::class, 'amount');
	}
}
