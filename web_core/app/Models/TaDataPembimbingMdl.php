<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaDataPembimbingMdl extends Model {
  protected $table = 'ta_data_pembimbing';
  protected $primaryKey = 'id';
  protected $fillable = ['ta_data_id','pembimbing_ke','pegawai_id','is_active','created_at','created_by','deleted_at','deleted_by','nilai_angka'];
	public $timestamps = false;

  public function scopeGetByid($q, $id){
    return $q->selectRaw('ta_data_pembimbing.*, p.gelar_depan as pegawai_gelar_depan, p.nama as pegawai_nama, p.gelar_belakang as pegawai_gelar_belakang')
      ->leftJoin('pegawai as p','ta_data_pembimbing.pegawai_id','=','p.id')
      ->where('ta_data_pembimbing.ta_data_id',$id);
  }


	public function scopeJoinAll($q, $ta_data_id){
		return $q->selectRaw('ta_data_pembimbing.*, pegawai.nama as pegawai_nama, pegawai.nip as pegawai_nip, pegawai.gelar_depan as pegawai_gelar_depan, pegawai.gelar_belakang as pegawai_gelar_belakang')
			->leftJoin('pegawai','ta_data_pembimbing.pegawai_id','=','pegawai.id')
			->where('ta_data_pembimbing.ta_data_id', $ta_data_id)->orderBy('ta_data_pembimbing.pembimbing_ke','asc');
	}


	public function scopeCmbJoinAll($q, $ta_data_id){
		return $q->selectRaw('ta_data_pembimbing.id as id,pegawai.nama as val, pegawai.nip as pegawai_nip, pegawai.gelar_depan as pegawai_gelar_depan, pegawai.gelar_belakang as pegawai_gelar_belakang')
			->leftJoin('pegawai','ta_data_pembimbing.pegawai_id','=','pegawai.id')
			->where('ta_data_pembimbing.ta_data_id', $ta_data_id)->orderBy('ta_data_pembimbing.pembimbing_ke','asc');
	}


	public function scopeCmbMahasiswaBimbingan($q, $pegawai_id){
		return $q->selectRaw('m.nim as id, m.nama as val, m.gelar_depan, m.gelar_belakang')
			->leftJoin('ta_data as p', 'ta_data_pembimbing.ta_data_id','=','p.id')
			->leftJoin('mahasiswa as m', 'p.mahasiswa_nim','=','m.nim')
			->where('ta_data_pembimbing.pegawai_id', $pegawai_id);
	}
  

}
