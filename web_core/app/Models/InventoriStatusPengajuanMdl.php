<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangKeluarMdl;

class InventoriStatusPengajuanMdl extends Model {
  protected $table = 'inventori_status_pengajuan';
  protected $primaryKey = 'kode';
  protected $fillable = ['nama','label','urutan','in_form','is_aktif'];

  public function usestatus() {
		return $this->hasMany(InventoriBarangKeluarMdl::class, 'status_ajuan', 'kode');
	}

  public function scopeCmb($q){
    return $q->selectRaw('kode as id, nama as val')->where('is_aktif','Y')->orderBy('urutan','asc');
  }
}
