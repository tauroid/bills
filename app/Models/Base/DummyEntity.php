<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DummyEntity
 * 
 * @property int $id
 * @property int $entity_id
 * @property int $owner_user_id
 * @property int|null $real_entity_id
 * 
 * @property Entity|null $entity
 * @property User $user
 *
 * @package App\Models\Base
 */
class DummyEntity extends Model
{
	protected $table = 'dummy_entity';
	public $timestamps = false;

	protected $casts = [
		'entity_id' => 'int',
		'owner_user_id' => 'int',
		'real_entity_id' => 'int'
	];

	public function entity()
	{
		return $this->belongsTo(Entity::class, 'real_entity_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'owner_user_id');
	}
}
