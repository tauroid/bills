<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Entity;
use App\Models\Settlement;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettlementParticipant
 * 
 * @property int $id
 * @property int $settlement_id
 * @property int $entity_id
 * 
 * @property Settlement $settlement
 * @property Entity $entity
 *
 * @package App\Models\Base
 */
class SettlementParticipant extends Model
{
	protected $table = 'settlement_participant';
	public $timestamps = false;

	protected $casts = [
		'settlement_id' => 'int',
		'entity_id' => 'int'
	];

	public function settlement()
	{
		return $this->belongsTo(Settlement::class);
	}

	public function entity()
	{
		return $this->belongsTo(Entity::class);
	}
}
