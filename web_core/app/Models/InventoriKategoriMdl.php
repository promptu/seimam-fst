<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangMdl;

class InventoriKategoriMdl extends Model {
  protected $table = 'inventori_kategori';
  protected $primaryKey = 'id';
  protected $fillable = ['nama','created_at','updated_at'];

  public function usebarang() {
		return $this->hasMany(InventoriBarangMdl::class, 'kategori_id', 'id');
	}

  public function scopeList($q, $f1){
    return $q->when($f1, function($w) use ($f1) {
      return $w->where('nama','like','%'.$f1.'%');
    })->orderBy('id','asc');
  }

  public function scopeCmb($q){
    return $q->selectRaw('id as id, nama as val')
             ->orderBy('nama', 'asc')->groupBy('nama','id');
  }

}
