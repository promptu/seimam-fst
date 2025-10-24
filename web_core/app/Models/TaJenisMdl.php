<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaJenisMdl extends Model {
  protected $table = 'ta_jenis';
  protected $primaryKey = 'kode';
  public $incrementing = false;

  public function scopeCmbJenis($q){
    return $q->selectRaw('kode as id, nama as val')->where('is_aktif','Y')->orderBy('urutan','asc');
  }

  public function scopeCmbJenjang($q){
    return $q->selectRaw('kode as id, jenjang_kode as val')->where('is_aktif','Y')->orderBy('urutan','asc');
  }
}

