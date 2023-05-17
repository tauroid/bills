<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Entity;
use App\Models\Name;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityName
 * 
 * @property int $id
 * @property int $name
 * @property int $entity
 * 
 *
 * @package App\Models\Base
 */
class EntityName extends Model
{
	protected $table = 'entity_name';
	public $timestamps = false;

	protected $casts = [
		'name' => 'int',
		'entity' => 'int'
	];

	public function name()
	{
		return $this->belongsTo(Name::class, 'name');
	}

	public function entity()
	{
		return $this->belongsTo(Entity::class, 'entity');
	}
}
