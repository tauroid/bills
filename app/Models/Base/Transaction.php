<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use App\Models\Settlement;
use App\Models\TransactionAllocation;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property string|null $evidence_link
 * @property string $type
 * @property int $amount_id
 * @property Carbon $created_at
 * @property bool $outlay
 * @property Carbon $updated_at
 * @property int $owner_id
 * @property string|null $deleted_at
 * @property string $description
 * 
 * @property Amount $amount
 * @property User $user
 * @property Collection|Settlement[] $settlements
 * @property Collection|TransactionAllocation[] $transaction_allocations
 * @property Collection|TransactionFrom[] $transaction_froms
 * @property Collection|TransactionTo[] $transaction_tos
 *
 * @package App\Models\Base
 */
class Transaction extends Model
{
	use SoftDeletes;
	protected $table = 'transaction';

	protected $casts = [
		'amount_id' => 'int',
		'outlay' => 'bool',
		'owner_id' => 'int'
	];

	public function amount()
	{
		return $this->belongsTo(Amount::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'owner_id');
	}

	public function settlements()
	{
		return $this->belongsToMany(Settlement::class, 'settlement_transactions')
					->withPivot('id');
	}

	public function transaction_allocations()
	{
		return $this->hasMany(TransactionAllocation::class, 'transaction');
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
