<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\DummyEntity;
use App\Models\Name;
use App\Models\SettlementParticipant;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Entity
 * 
 * @property int $id
 * 
 * @property DummyEntity $dummy_entity
 * @property Collection|DummyEntity[] $dummy_entities
 * @property Collection|Name[] $names
 * @property Collection|SettlementParticipant[] $settlement_participants
 * @property Collection|TransactionFrom[] $transaction_froms
 * @property Collection|TransactionTo[] $transaction_tos
 * @property Collection|User[] $users
 *
 * @package App\Models\Base
 */
class Entity extends Model
{
	protected $table = 'entity';
	public $timestamps = false;

	public function dummy_entity()
	{
		return $this->hasOne(DummyEntity::class);
	}

	public function dummy_entities()
	{
		return $this->hasMany(DummyEntity::class, 'real_entity_id');
	}

	public function names()
	{
		return $this->belongsToMany(Name::class, 'entity_name', 'entity', 'name')
					->withPivot('id');
	}

	public function settlement_participants()
	{
		return $this->hasMany(SettlementParticipant::class);
	}

	public function transaction_froms()
	{
		return $this->hasMany(TransactionFrom::class);
	}

	public function transaction_tos()
	{
		return $this->hasMany(TransactionTo::class);
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_entity', 'entity', 'user')
					->withPivot('id');
	}
}
