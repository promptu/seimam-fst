<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakPengajuanMdl extends Model {
  protected $table = 'bak_pengajuan';
	protected $primaryKey = 'id';
	protected $fillable = ['tgl_pengajuan','mahasiswa_nim','unit_kerja_kode','bak_template_kode','keperluan','status','keterangan_status','syarat_valid','berkas_output','created_at','created_by','updated_at','updated_by'];
	public $timestamps = false;

	public function scopeListByMhs($q, $nim, $f1){
		return $q->selectRaw('bak_pengajuan.*, bak_template.nama as bak_template_nama, unit_kerja.nama as unit_kerja_nama, mahasiswa.nama as mahasiswa_nama')
			->leftJoin('bak_template', 'bak_pengajuan.bak_template_kode', '=', 'bak_template.kode')
			->leftJoin('unit_kerja', 'bak_pengajuan.unit_kerja_kode', '=', 'unit_kerja.kode')
			->leftJoin('mahasiswa', 'bak_pengajuan.mahasiswa_nim', '=', 'mahasiswa.nim')
			->where('mahasiswa_nim', $nim)
			->when($f1, function($w, $f1){
				return $w->where('bak_pengajuan.bak_template_kode', $f1);
			})
			->orderBy('id', 'asc');
	}

	public function scopeById($q, $id){
		return $q->selectRaw('bak_pengajuan.*, bak_template.nama as bak_template_nama, unit_kerja.nama as unit_kerja_nama, mahasiswa.nama as mahasiswa_nama, mahasiswa.gelar_depan as mahasiswa_gelar_depan, mahasiswa.gelar_belakang as mahasiswa_gelar_belakang')
			->leftJoin('bak_template', 'bak_pengajuan.bak_template_kode', '=', 'bak_template.kode')
			->leftJoin('unit_kerja', 'bak_pengajuan.unit_kerja_kode', '=', 'unit_kerja.kode')
			->leftJoin('mahasiswa', 'bak_pengajuan.mahasiswa_nim', '=', 'mahasiswa.nim')
			->when($id, function($w, $id){
				return $w->where('bak_pengajuan.id', $id);
			})
			->orderBy('id', 'asc');
	}
}
