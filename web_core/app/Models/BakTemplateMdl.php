<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakTemplateMdl extends Model {
	protected $table = 'bak_template';
	protected $primaryKey = 'kode';
	protected $fillable = ['kode','nama','is_aktif','urutan'];
	public $timestamps = false;
	public $incrementing = false;

	public function scopeCmb($q){
		return $q->selectRaw('kode as id, nama as val')->where('is_aktif','Y')->orderBy('urutan','asc');
	}
}
