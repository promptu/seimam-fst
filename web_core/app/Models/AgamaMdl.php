<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgamaMdl extends Model {
	protected $table = 'agama';
	protected $primaryKey = 'id';
	protected $fillable = ['nama', 'is_aktif'];
	public $timestamps = false;
	public $incrementing = false;

	public function scopeCmb($q){
		return $q->selectRaw('id, nama as val')->where('is_aktif','Y')->orderBy('id','asc');
	}
}
