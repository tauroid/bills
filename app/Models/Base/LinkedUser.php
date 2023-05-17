<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkedUser
 * 
 * @property int $id
 * @property Carbon $created_at
 * @property int $user_a
 * @property int $user_b
 * 
 * @property User $user
 *
 * @package App\Models\Base
 */
class LinkedUser extends Model
{
	protected $table = 'linked_users';
	public $timestamps = false;

	protected $casts = [
		'user_a' => 'int',
		'user_b' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_b');
	}
}
