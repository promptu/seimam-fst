<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataWilayahMdl extends Model {
  protected $table = 'data_wilayah';
  protected $primaryKey = 'kode';
  protected $fillable = ['kode','nama'];
  public $timestamps = false;
  public $incrementing = false;
}
