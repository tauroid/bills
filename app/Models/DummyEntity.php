<?php

namespace App\Models;

use App\Models\Base\DummyEntity as BaseDummyEntity;

class DummyEntity extends BaseDummyEntity
{
	public function entity()
	{
		return $this->belongsTo(Entity::class, 'entity_id');
	}

	public function real_entity()
	{
		return $this->belongsTo(Entity::class, 'real_entity_id');
	}

	public function owner_user()
	{
		return $this->user();
	}
}
