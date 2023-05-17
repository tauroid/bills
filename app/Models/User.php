<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Base\User as BaseUser;
use Illuminate\Support\Facades\Auth;

// Implementing all these interfaces and traits instead of extending Laravel's
// User because we need to extend BaseUser
class User extends BaseUser implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail,
	    HasFactory, Notifiable, HasApiTokens;

	protected $hidden = [
		'password',
		'two_factor_secret',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'email_verified_at',
		'password',
		'two_factor_secret',
		'two_factor_recovery_codes',
		'two_factor_confirmed_at',
		'remember_token'
	];

	public function allLinkedUsers()
	{
		return $this->linked_users_left()->get()
					->map(fn($linkedUser) => $linkedUser->user_right)
					->merge($this->linked_users_right()->get()
								 ->map(fn($linkedUser) => $linkedUser->user_left));
	}

	public function linked_users_left()
	{
		return $this->hasMany(LinkedUser::class, 'user_a');
	}

	public function linked_users_right()
	{
		return $this->hasMany(LinkedUser::class, 'user_b');
	}

	public function knowsEntity(Entity $entity): bool
	{
		$entityUser = $entity->users->isNotEmpty()
					? $entity->users[0] : null;

		if ($entityUser?->id === Auth::id()) return true;

		if ($entityUser?->allLinkedUsers()->some(
			fn ($user) => $user->id === Auth::id()))
		{
			return true;
		}

		return $entity->dummy_entity?->owner_user->id
			=== Auth::id();
	}
}
