<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenjangMdl extends Model {
  protected $table = 'jenjang';
  protected $primaryKey = 'kode';
  protected $fillable = ['kode','nama','is_perguruan_tinggi','is_pasca','urutan','is_aktif','created_at','created_by','updated_at','updated_by'];
  public $timestamps = false;
  public $incrementing = false;
}
