<?php

namespace App\Models;

use App\Models\Base\LinkedUser as BaseLinkedUser;

class LinkedUser extends BaseLinkedUser
{
	public function user_left()
	{
		return $this->belongsTo(User::class, 'user_a');
	}

	public function user_right()
	{
		return $this->belongsTo(User::class, 'user_b');
	}
}
