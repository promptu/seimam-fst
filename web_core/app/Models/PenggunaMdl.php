<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaMdl extends Model {
  protected $table = 'pengguna';
  protected $primaryKey = 'id';
  protected $fillable = ['nama','username','password','pegawai_id','mahasiswa_nim','email','pict','is_def_password','def_password','last_login_ip','last_login_time','is_aktif','created_at','created_by','updated_at','updated_by'];
  public $timestamps = false;

	public function scopeList($q, $f1, $f2){
		return $q->when($f1, function($w) use ($f1){
      return $w->whereRaw('(username like ? or nama like ?)', ['%'.$f1.'%', '%'.$f1.'%']);
    })->when($f2, function($w) use ($f2){
      return $w->where('is_aktif', $f2);
    })->orderBy('nama','asc');
	}

  public function scopeGetByid($q, $id){
    return $q->selectRaw('pengguna.*, pegawai.nama as pegawai_nama, pegawai.gelar_depan as pegawai_gelar_depan, pegawai.gelar_belakang as pegawai_gelar_belakang, mahasiswa.nama as mahasiswa_nama, mahasiswa.gelar_depan as mahasiswa_gelar_depan, mahasiswa.gelar_belakang as mahasiswa_gelar_belakang')
      ->leftJoin('pegawai','pengguna.pegawai_id','=','pegawai.id')
      ->leftJoin('mahasiswa','pengguna.mahasiswa_nim','=','mahasiswa.nim')
      ->where('pengguna.id',$id);
  }
}
