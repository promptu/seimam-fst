<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaProposalSyaratUjianMdl extends Model {
  protected $table = 'ta_proposal_syarat_ujian';
  protected $primaryKey = 'id';
  protected $fillable = ['ta_jenis_kode','unit_kerja_kode','nama','is_aktif','created_at','created_by','updated_at','updated_by'];
  public $timestamps = false;

	public function scopeList($q, $f1, $f2){
		return $q->selectRaw('ta_proposal_syarat_ujian.*, ta_jenis.nama as ta_jenis_nama')
			->leftJoin('ta_jenis', 'ta_proposal_syarat_ujian.ta_jenis_kode', '=', 'ta_jenis.kode')
			->when($f1, function($w) use ($f1){
				return $w->where('unit_kerja_kode', $f1);
			})->when($f2, function($w) use ($f2){
				return $w->where('ta_jenis_kode',$f2);
			})->orderBy('nama','asc');
	}

	public function scopeListByProposal($q, $unit_kode, $ta_jenis_kode, $proposal_id){
		return $q->selectRaw('ta_proposal_syarat_ujian.*, upl.berkas, upl.is_valid, upl.uploaded_at, upl.uploaded_by, upl.validated_at, upl.validated_by')
			// ->leftJoin('ta_proposal_syarat_ujian_upload as upl', function($join) use ($proposal_id) {
			// 	$join->on('ta_proposal_syarat_ujian.id','=','upl.ta_proposal_syarat_ujian_id');
			// })
			->leftJoin('ta_proposal_syarat_ujian_upload as upl', 'ta_proposal_syarat_ujian.id','=','upl.ta_proposal_syarat_ujian_id')
			->where('ta_proposal_syarat_ujian.ta_jenis_kode', $ta_jenis_kode)
			->where('ta_proposal_syarat_ujian.unit_kerja_kode', $unit_kode)
			->orderBy('ta_proposal_syarat_ujian.nama', 'asc');
	}

	public function scopeListByProposal2($q, $unit_kode, $ta_jenis_kode, $proposal_id){
		return $q->where('ta_jenis_kode', $ta_jenis_kode)
			->where('unit_kerja_kode', $unit_kode)
			->where('is_aktif','Y')
			->orderBy('nama', 'asc');
	}



}
