<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakSyaratMdl extends Model {
  protected $table = 'bak_syarat';
	protected $primaryKey = 'id';
	protected $fillable = ['unit_kerja_kode','bak_template_kode','nama','is_aktif','created_at','created_by','updated_at','updated_by'];
	public $timestamps = false;

	public function scopeList($q, $f1, $f2){
		return $q->selectRaw('bak_syarat.*, unit_kerja.nama as unit_kerja_nama, unit_kerja.level, bak_template.nama as template_nama')
			->leftJoin('unit_kerja','bak_syarat.unit_kerja_kode','=','unit_kerja.kode')
			->leftJoin('bak_template','bak_syarat.bak_template_kode','=','bak_template.kode')
			->when($f1, function($w, $f1){
				return $w->where('bak_syarat.unit_kerja_kode', $f1);
			})
			->when($f2, function($w, $f2){
				return $w->where('bak_syarat.bak_template_kode',$f2);
			})
			->orderBy('unit_kerja.urutan','asc')
			->orderBy('bak_syarat.bak_template_kode','asc')
			->orderBy('bak_syarat.nama','asc');
	}


	public function scopeJoinPengajuanSyarat($q, $unit_kerja_kode, $template_kode, $pengajuan_id){
		return $q->selectRaw('bak_syarat.*, bak_pengajuan_syarat.berkas, bak_pengajuan_syarat.is_valid, bak_pengajuan_syarat.validated_at, bak_pengajuan_syarat.validated_by, bak_pengajuan_syarat.validated_msg')
			->leftJoin('bak_pengajuan_syarat','bak_syarat.id','=','bak_pengajuan_syarat.bak_syarat_id')
			->where('bak_syarat.is_aktif', 'Y')
			->where('bak_syarat.unit_kerja_kode', $unit_kerja_kode)
			->where('bak_syarat.bak_template_kode', $template_kode);
	}
}
