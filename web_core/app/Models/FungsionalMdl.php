<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FungsionalMdl extends Model {
	protected $table = 'fungsional';
	protected $primaryKey = 'kode';
	protected $fillable = ['kode','nama'];
	public $timestamps = false;
	public $incrementing = false;

  public function scopeCmb($q){
    return $q->selectRaw('kode as id, nama as val')->orderBy('nama','asc');
  }
}
