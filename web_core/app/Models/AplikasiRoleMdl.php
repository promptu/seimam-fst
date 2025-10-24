<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplikasiRoleMdl extends Model {
	protected $table = 'aplikasi_role';
	protected $primaryKey = 'id';
  protected $fillable = ['nama','urutan','is_aktif','created_at','created_by','updated_at','updated_by'];
  public $timestamps = false;

	public function scopeCmbByid($q, $id){
		return $q->selectRaw('id, nama as val')->where('aplikasi_id',$id)->orderBy('nama','asc');
	}
}
