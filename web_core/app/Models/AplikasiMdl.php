<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplikasiMdl extends Model {
  protected $table = 'aplikasi';
	protected $primaryKey = 'id';

	public function scopeCmb($q){
		return $q->selectRaw('id, nama as val')->orderBy('urutan','asc');
	}
}
