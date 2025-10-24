<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaProposalPengujiMdl extends Model {
	protected $table = 'ta_proposal_penguji';
	protected $primaryKey = 'id';
	protected $fillable = ['ta_proposal_id','pegawai_id','created_at','created_by','nilai_angka'];
  public $timestamps = false;

  public function scopeCmb($q){
    return $q->selectRaw('id, nama as val')->where('is_aktif','Y')->orderBy('urutan','asc');
  }

  public function scopeGetByProposalId($q, $id){
    return $q->selectRaw('ta_proposal_penguji.*, pegawai.nip as pegawai_nip, pegawai.nama as pegawai_nama, pegawai.gelar_depan as pegawai_gelar_depan, pegawai.gelar_belakang as pegawai_gelar_belakang')
    ->leftJoin('pegawai','ta_proposal_penguji.pegawai_id','=','pegawai.id')
    ->where('ta_proposal_penguji.ta_proposal_id',$id)
    ->orderBy('id','asc');
  }
}
