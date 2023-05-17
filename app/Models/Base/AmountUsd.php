<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AmountUsd
 * 
 * @property int $id
 * @property int $dollars
 * @property int $cents
 * @property int $amount
 * 
 *
 * @package App\Models\Base
 */
class AmountUsd extends Model
{
	protected $table = 'amount_usd';
	public $timestamps = false;

	protected $casts = [
		'dollars' => 'int',
		'cents' => 'int',
		'amount' => 'int'
	];

	public function amount()
	{
		return $this->belongsTo(Amount::class, 'amount');
	}
}
