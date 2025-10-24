<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangKeluarDetailMdl;

class InventoriStatusPengajuanDetailMdl extends Model {
  protected $table = 'inventori_status_pengajuan_detail';
  protected $primaryKey = 'kode';
  protected $fillable = ['nama','label','urutan','in_form','is_aktif'];

  public function usestatus() {
		return $this->hasMany(InventoriBarangKeluarDetailMdl::class, 'status', 'kode');
	}

  public function scopeCmb($q){
		return $q->selectRaw('kode as id, nama as val')->orderBy('kode','asc');
	}

}
