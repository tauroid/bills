<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Settlement;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SettlementAdmin
 * 
 * @property int $id
 * @property int $settlement_id
 * @property int $user_id
 * 
 * @property Settlement $settlement
 * @property User $user
 *
 * @package App\Models\Base
 */
class SettlementAdmin extends Model
{
	protected $table = 'settlement_admins';
	public $timestamps = false;

	protected $casts = [
		'settlement_id' => 'int',
		'user_id' => 'int'
	];

	public function settlement()
	{
		return $this->belongsTo(Settlement::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
