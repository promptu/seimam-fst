<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeMdl extends Model {
  protected $table = 'periode';
  protected $primaryKey = 'kode';
  protected $fillable = ['kode','nama','nama_singkat','tahun_ajaran','tanggal_awal','tanggal_akhir','tanggal_awal_uts','tanggal_akhir_uts','tanggal_awal_uas','tanggal_akhir_uas','is_aktif'];

  public $timestamps = false;
  public $incrementing = false;

  public function scopeCmb($q){
    return $q->selectRaw('kode as id, nama as val')->orderBy('kode','desc');
  }

  public function scopeList($q){
    return $q->orderBy('kode','desc');
  }
}
