<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusKepegawaianMdl extends Model {
	protected $table = 'status_kepegawaian';
	protected $primaryKey = 'kode';
	protected $fillable = ['kode','nama'];
	public $timestamps = false;
	public $incrementing = false;
}
