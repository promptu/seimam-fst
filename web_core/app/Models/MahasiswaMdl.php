<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaMdl extends Model {
  protected $table = 'mahasiswa';
  protected $primaryKey = 'nim';
  protected $fillable = ['nim','nisn','npsn','periode_id','periode_terakhir_id','agama_id','program_studi_kode','jenjang_kode','status_mahasiswa_kode','nik','nama','gelar_depan','gelar_belakang','kecamatan_kode','kota_kode','provinsi_kode','negara_kode','tempat_lahir','tanggal_lahir','jenis_kelamin','email','email_kampus','sks_lulus'];
  public $timestamps = false;
  public $incrementing = false;

	public function scopeSelCmb($q, $s){
		return $q->where('nim','like','%'.$s.'%')
			->orWhere('nama','like','%'.$s.'%')
			->orderBy('nim','asc')->limit(5);
	}
  
  public function scopeList($q, $f1, $f2, $f3){
    return $q->selectRaw('mahasiswa.*, unit_kerja.nama as program_studi_nama, unit_kerja.level as program_studi_level, status_mahasiswa.nama as status_mahasiswa_nama')
      ->leftJoin('unit_kerja','mahasiswa.program_studi_kode','=','unit_kerja.kode')
      ->leftJoin('status_mahasiswa','mahasiswa.status_mahasiswa_kode','=','status_mahasiswa.kode')
      ->when($f1, function($w) use ($f1){
				return $w->whereRaw('unit_kerja.urutan like (select concat(urutan,"%") from unit_kerja where kode=?)', [$f1]);
      })
      ->when($f2, function($w) use ($f2){
        return $w->whereRaw('(mahasiswa.nim like ? or mahasiswa.nama like ?)', ['%'.$f2.'%','%'.$f2.'%']);
      })
      ->when($f3, function($w) use ($f3){
        return $w->where('status_mahasiswa_kode', $f3);
      })
      ->orderBy('unit_kerja.urutan','asc')->orderBy('mahasiswa.nim','asc');
  }
  
  public function scopeGetByid($q, $id){
    return $q->selectRaw('mahasiswa.*, unit_kerja.nama as program_studi_nama, unit_kerja.level as program_studi_level, status_mahasiswa.nama as status_mahasiswa_nama, jenjang.nama as jenjang_nama')
      ->leftJoin('unit_kerja','mahasiswa.program_studi_kode','=','unit_kerja.kode')
      ->leftJoin('status_mahasiswa','mahasiswa.status_mahasiswa_kode','=','status_mahasiswa.kode')
      ->leftJoin('jenjang', 'mahasiswa.jenjang_kode','=','jenjang.kode')
      ->where('mahasiswa.nim', $id);
  }

}
