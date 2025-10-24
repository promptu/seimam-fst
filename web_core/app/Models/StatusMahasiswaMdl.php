<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusMahasiswaMdl extends Model {
  protected $table = 'status_mahasiswa';
  protected $primaryKey = 'kode';
  protected $fillable = ['kode','nama','is_diajukan','is_kuliah','is_aktif'];
  public $timestamps = false;
  public $incrementing = false;

  public function scopeCmb($q){
    return $q->selectRaw('kode as id, nama as val')->where('is_aktif','Y')->orderBy('kode','asc');
  }
}
