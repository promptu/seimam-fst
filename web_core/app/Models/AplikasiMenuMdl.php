<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplikasiMenuMdl extends Model {
  protected $table = 'aplikasi_menu';
  protected $primaryKey = 'id';


	public function scopeGetByid($q, $id){
		return $q->where('aplikasi_id',$id)->where('is_aktif','Y')->orderBy('urutan','asc');
	}
}
