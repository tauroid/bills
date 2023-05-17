<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AmountShare
 * 
 * @property int $id
 * @property int $amount
 * @property int $share
 * 
 *
 * @package App\Models\Base
 */
class AmountShare extends Model
{
	protected $table = 'amount_share';
	public $timestamps = false;

	protected $casts = [
		'amount' => 'int',
		'share' => 'int'
	];

	public function amount()
	{
		return $this->belongsTo(Amount::class, 'amount');
	}
}
