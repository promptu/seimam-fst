<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegaraMdl extends Model {
  protected $table = 'negara';
  protected $primaryKey = 'kode';
  protected $fillable = ['kode','nama','urutan','is_aktif'];
  public $timestamps = false;
  public $incrementing = false;
}
