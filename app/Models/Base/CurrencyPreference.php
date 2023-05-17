<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CurrencyPreference
 * 
 * @property int $id
 * @property int $user_id
 * @property string $currency
 * 
 * @property User $user
 *
 * @package App\Models\Base
 */
class CurrencyPreference extends Model
{
	protected $table = 'currency_preference';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
