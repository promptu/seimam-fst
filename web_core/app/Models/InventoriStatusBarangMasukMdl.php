<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangMasukDetailMdl;

class InventoriStatusBarangMasukMdl extends Model {
  protected $table = 'inventori_status_barang_masuk';
  protected $primaryKey = 'kode';
  protected $fillable = ['nama','label','urutan','in_form','is_aktif'];

  public function usestatus() {
		return $this->hasMany(InventoriBarangMasukMdl::class, 'status', 'kode');
	}

  public function scopeCmb($q){
		return $q->selectRaw('kode as id, nama as val')->orderBy('kode','asc');
	}

}
