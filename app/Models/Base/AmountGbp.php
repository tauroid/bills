<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AmountGbp
 * 
 * @property int $id
 * @property int $pounds
 * @property int $pence
 * @property int $amount
 * 
 *
 * @package App\Models\Base
 */
class AmountGbp extends Model
{
	protected $table = 'amount_gbp';
	public $timestamps = false;

	protected $casts = [
		'pounds' => 'int',
		'pence' => 'int',
		'amount' => 'int'
	];

	public function amount()
	{
		return $this->belongsTo(Amount::class, 'amount');
	}
}
