<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Settlement;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettlementTransaction
 * 
 * @property int $id
 * @property int $settlement_id
 * @property int $transaction_id
 * 
 * @property Settlement $settlement
 * @property Transaction $transaction
 *
 * @package App\Models\Base
 */
class SettlementTransaction extends Model
{
	protected $table = 'settlement_transactions';
	public $timestamps = false;

	protected $casts = [
		'settlement_id' => 'int',
		'transaction_id' => 'int'
	];

	public function settlement()
	{
		return $this->belongsTo(Settlement::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}
