<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\SettlementAdmin;
use App\Models\SettlementParticipant;
use App\Models\Transaction;
use App\Models\TransactionAllocation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Settlement
 * 
 * @property int $id
 * @property string|null $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $owner_id
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|SettlementAdmin[] $settlement_admins
 * @property Collection|SettlementParticipant[] $settlement_participants
 * @property Collection|Transaction[] $transactions
 * @property Collection|TransactionAllocation[] $transaction_allocations
 *
 * @package App\Models\Base
 */
class Settlement extends Model
{
	use SoftDeletes;
	protected $table = 'settlement';

	protected $casts = [
		'owner_id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'owner_id');
	}

	public function settlement_admins()
	{
		return $this->hasMany(SettlementAdmin::class);
	}

	public function settlement_participants()
	{
		return $this->hasMany(SettlementParticipant::class);
	}

	public function transactions()
	{
		return $this->belongsToMany(Transaction::class, 'settlement_transactions')
					->withPivot('id');
	}

	public function transaction_allocations()
	{
		return $this->hasMany(TransactionAllocation::class, 'settlement');
	}
}
