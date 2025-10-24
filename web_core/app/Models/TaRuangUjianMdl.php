<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaRuangUjianMdl extends Model {
	protected $table = 'ta_ruang_ujian';
	protected $primaryKey = 'kode';
	protected $fillable = ['kode','nama','keterangan','is_aktif','unit_kerja_kode','created_at','created_by','updated_at','updated_by'];
  public $timestamps = false;
  public $incrementing = false;

  public function scopeList($q, $f1, $f2, $f3){
    return $q->selectRaw('ta_ruang_ujian.*, unit_kerja.nama as unit_kerja_nama')
    ->leftJoin('unit_kerja','ta_ruang_ujian.unit_kerja_kode','=','unit_kerja.kode')
    ->when($f1, function($w) use ($f1) {
      return $w->where('ta_ruang_ujian.nama','like','%'.$f1.'%');
    })->when($f2, function($w) use ($f2) {
      return $w->where('ta_ruang_ujian.is_aktif',$f2);
    })->when($f3, function($w) use ($f3) {
      return $w->where('ta_ruang_ujian.unit_kerja_kode',$f3);
    })->orderBy('nama','asc');
  }

  public function scopeCmb($q){
    return $q->selectRaw('ta_ruang_ujian.kode as id, ta_ruang_ujian.nama as val, unit_kerja.nama as unit_kerja_nama')
      ->leftJoin('unit_kerja','ta_ruang_ujian.unit_kerja_kode','=','unit_kerja.kode')
      ->where('ta_ruang_ujian.is_aktif','Y')
      ->orderBy('unit_kerja.kode','asc')
      ->orderBy('ta_ruang_ujian.nama','asc');
  }

}
