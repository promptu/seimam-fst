<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttemptMdl extends Model {
	protected $table = 'login_attempt';
	protected $primaryKey = 'id';
	protected $fillable = ['username','attempt','expire'];

	public $timestamps = false;
}
