<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMdl extends Model {
	protected $table = 'role';
	protected $primaryKey = 'id';
	protected $fillable = ['nama','urutan','is_super_admin','is_aktif','is_edit','created_at','created_by','updated_at','updated_by'];

  public function scopeCmb($q){
    return $q->selectRaw('id, nama as val')->where('is_aktif','Y')->orderBy('urutan','asc');
  }

  public function scopeList($q, $f1, $f2){
    return $q->when($f1, function($w) use ($f1) {
      return $w->where('nama','like','%'.$f1.'%');
    })->when($f2, function($w) use ($f2) {
      return $w->where('is_aktif',$f2);
    })->orderBy('urutan','asc');
  }
}
