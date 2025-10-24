<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaProposalPembimbingMdl extends Model {
  protected $table = 'ta_proposal_pembimbing';
  protected $primaryKey = 'id';
  protected $fillable = ['ta_proposal_id','pembimbing_ke','pegawai_id','is_active','created_at','created_by','deleted_at','deleted_by','nilai_angka'];
	public $timestamps = false;

  public function scopeGetByid($q, $id){
    return $q->selectRaw('ta_proposal_pembimbing.*, p.gelar_depan as pegawai_gelar_depan, p.nama as pegawai_nama, p.gelar_belakang as pegawai_gelar_belakang')
      ->leftJoin('pegawai as p','ta_proposal_pembimbing.pegawai_id','=','p.id')
      ->where('ta_proposal_pembimbing.ta_proposal_id',$id);
  }


	public function scopeJoinAll($q, $ta_proposal_id){
		return $q->selectRaw('ta_proposal_pembimbing.*, pegawai.nama as pegawai_nama, pegawai.nip as pegawai_nip, pegawai.gelar_depan as pegawai_gelar_depan, pegawai.gelar_belakang as pegawai_gelar_belakang')
			->leftJoin('pegawai','ta_proposal_pembimbing.pegawai_id','=','pegawai.id')
			->where('ta_proposal_pembimbing.ta_proposal_id', $ta_proposal_id)->orderBy('ta_proposal_pembimbing.pembimbing_ke','asc');
	}


	public function scopeCmbJoinAll($q, $ta_proposal_id){
		return $q->selectRaw('ta_proposal_pembimbing.id as id,pegawai.nama as val, pegawai.nip as pegawai_nip, pegawai.gelar_depan as pegawai_gelar_depan, pegawai.gelar_belakang as pegawai_gelar_belakang')
			->leftJoin('pegawai','ta_proposal_pembimbing.pegawai_id','=','pegawai.id')
			->where('ta_proposal_pembimbing.ta_proposal_id', $ta_proposal_id)->orderBy('ta_proposal_pembimbing.pembimbing_ke','asc');
	}


	public function scopeCmbMahasiswaBimbingan($q, $pegawai_id){
		return $q->selectRaw('m.nim as id, m.nama as val, m.gelar_depan, m.gelar_belakang')
			->leftJoin('ta_proposal as p', 'ta_proposal_pembimbing.ta_proposal_id','=','p.id')
			->leftJoin('mahasiswa as m', 'p.mahasiswa_nim','=','m.nim')
			->where('ta_proposal_pembimbing.is_active','Y')
			->where('ta_proposal_pembimbing.pegawai_id', $pegawai_id);
	}
  

}
