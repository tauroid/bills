<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Name
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|Entity[] $entities
 *
 * @package App\Models\Base
 */
class Name extends Model
{
	protected $table = 'name';
	public $timestamps = false;

	public function entities()
	{
		return $this->belongsToMany(Entity::class, 'entity_name', 'name', 'entity')
					->withPivot('id');
	}
}
