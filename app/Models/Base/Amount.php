<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\AmountGbp;
use App\Models\AmountPercentage;
use App\Models\AmountShare;
use App\Models\AmountUsd;
use App\Models\Transaction;
use App\Models\TransactionAllocation;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Amount
 * 
 * @property int $id
 * @property string $currency
 * 
 * @property AmountGbp $amount_gbp
 * @property AmountPercentage $amount_percentage
 * @property AmountShare $amount_share
 * @property AmountUsd $amount_usd
 * @property Collection|Transaction[] $transactions
 * @property Collection|TransactionAllocation[] $transaction_allocations
 * @property Collection|TransactionFrom[] $transaction_froms
 * @property Collection|TransactionTo[] $transaction_tos
 *
 * @package App\Models\Base
 */
class Amount extends Model
{
	protected $table = 'amount';
	public $timestamps = false;

	public function amount_gbp()
	{
		return $this->hasOne(AmountGbp::class, 'amount');
	}

	public function amount_percentage()
	{
		return $this->hasOne(AmountPercentage::class, 'amount');
	}

	public function amount_share()
	{
		return $this->hasOne(AmountShare::class, 'amount');
	}

	public function amount_usd()
	{
		return $this->hasOne(AmountUsd::class, 'amount');
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}

	public function transaction_allocations()
	{
		return $this->hasMany(TransactionAllocation::class, 'amount');
	}

	public function transaction_froms()
	{
		return $this->hasMany(TransactionFrom::class);
	}

	public function transaction_tos()
	{
		return $this->hasMany(TransactionTo::class);
	}
}
