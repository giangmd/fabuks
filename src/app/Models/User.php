<?php
namespace App\Models;

use \Laravel\Passport\HasApiTokens;
use \Illuminate\Contracts\Auth\MustVerifyEmail;
use \Illuminate\Foundation\Auth\User as Authenticatable;
use \Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
	use HasApiTokens, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'first_name', 'last_name', 'email',
		'password', 'phone_number', 'role',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	protected static function boot() {
		parent::boot();
		static::saved(function ($model) {
			if (count($model->balance) <= 0) {
				$balanceData = [];
				array_push($balanceData, [
					'type' => config('settings.fabuk_symbool'),
					'balance' => config('settings.init_gain')
				]);
				$currenies = config('settings.currenies');
				foreach ($currenies as $key => $curreny) {
					array_push($balanceData, [
						'type' => $curreny,
						'balance' => 0
					]);
				}

				array_push($balanceData, [
					'type' => 'total',
					'balance' => config('settings.init_gain')
				]);

				$model->balance()->createMany($balanceData);
			}
		});
	}

	public function balance() {
		return $this->hasMany(UserBalance::class);
	}

	public function tradeHistory() {
		return $this->hasMany(TradeHistory::class);
	}

	public function getRoleAttribute($value) {
		return config('settings.role')[$value] ?? $value;
	}

	public function hasRole(string $role) {
		if (!in_array($role, config('settings.role'))) {
			return false;
		}

		return $this->role === $role || $role === 'admin';
	}
}
