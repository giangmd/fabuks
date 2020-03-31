<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class UserBalance extends Model {
	protected $fillable = [
		'user_id', 'balance', 'type'
	];

	public function user() {
		return $this->beLongsTo(User::class);
	}
}
