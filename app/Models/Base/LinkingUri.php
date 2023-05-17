<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkingUri
 * 
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $expires
 * @property int $target_user
 * @property string $uri
 * 
 * @property User $user
 *
 * @package App\Models\Base
 */
class LinkingUri extends Model
{
	protected $table = 'linking_uris';
	public $timestamps = false;

	protected $casts = [
		'expires' => 'datetime',
		'target_user' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'target_user');
	}
}
