<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaRoleMdl extends Model {
  protected $table = 'pengguna_role';
  protected $primaryKey = 'id';
  protected $fillable = ['pengguna_id','role_id','unit_kerja_kode','created_at','created_by','updated_at','updated_by'];
  public $timestamps = false;

  public function scopeGetByid($q, $pengguna){
    return $q->selectRaw('pengguna_role.*, role.nama as role_nama, unit_kerja.nama as unit_kerja_nama, unit_kerja.level as unit_kerja_level')
      ->leftJoin('role','pengguna_role.role_id','=','role.id')
      ->leftJoin('unit_kerja','unit_kerja_kode','=','unit_kerja.kode')
      ->where('pengguna_role.pengguna_id',$pengguna)
      ->orderBy('role.urutan','asc');
  }
}
