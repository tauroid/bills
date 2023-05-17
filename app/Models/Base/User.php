<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\CurrencyPreference;
use App\Models\DummyEntity;
use App\Models\Entity;
use App\Models\LinkedUser;
use App\Models\LinkingUri;
use App\Models\Settlement;
use App\Models\SettlementAdmin;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CurrencyPreference $currency_preference
 * @property Collection|DummyEntity[] $dummy_entities
 * @property Collection|LinkedUser[] $linked_users
 * @property LinkingUri $linking_uri
 * @property Collection|Settlement[] $settlements
 * @property Collection|SettlementAdmin[] $settlement_admins
 * @property Collection|Transaction[] $transactions
 * @property Collection|Entity[] $entities
 *
 * @package App\Models\Base
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'two_factor_confirmed_at' => 'datetime'
	];

	public function currency_preference()
	{
		return $this->hasOne(CurrencyPreference::class);
	}

	public function dummy_entities()
	{
		return $this->hasMany(DummyEntity::class, 'owner_user_id');
	}

	public function linked_users()
	{
		return $this->hasMany(LinkedUser::class, 'user_b');
	}

	public function linking_uri()
	{
		return $this->hasOne(LinkingUri::class, 'target_user');
	}

	public function settlements()
	{
		return $this->hasMany(Settlement::class, 'owner_id');
	}

	public function settlement_admins()
	{
		return $this->hasMany(SettlementAdmin::class);
	}

	public function transactions()
	{
		return $this->hasMany(Transaction::class, 'owner_id');
	}

	public function entities()
	{
		return $this->belongsToMany(Entity::class, 'user_entity', 'user', 'entity')
					->withPivot('id');
	}
}
