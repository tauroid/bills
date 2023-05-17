<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserEntity
 * 
 * @property int $id
 * @property int $user
 * @property int $entity
 * 
 *
 * @package App\Models\Base
 */
class UserEntity extends Model
{
	protected $table = 'user_entity';
	public $timestamps = false;

	protected $casts = [
		'user' => 'int',
		'entity' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user');
	}

	public function entity()
	{
		return $this->belongsTo(Entity::class, 'entity');
	}
}
