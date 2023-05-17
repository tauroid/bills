<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AmountPercentage
 * 
 * @property int $id
 * @property int $amount
 * @property float $percentage
 * 
 *
 * @package App\Models\Base
 */
class AmountPercentage extends Model
{
	protected $table = 'amount_percentage';
	public $timestamps = false;

	protected $casts = [
		'amount' => 'int',
		'percentage' => 'float'
	];

	public function amount()
	{
		return $this->belongsTo(Amount::class, 'amount');
	}
}
