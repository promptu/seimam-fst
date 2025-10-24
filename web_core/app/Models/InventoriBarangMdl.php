<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriKategoriMdl;

class InventoriBarangMdl extends Model {
  protected $table = 'inventori_barang';
  protected $primaryKey = 'id';
  protected $fillable = ['nama','kode_barang','kategori_id','jumlah_stock','satuan','img','created_at','updated_at'];


  // Scope untuk Select2 pencarian barang
  public function scopeSelCmb($q, $s) {
    return $q->where('nama', 'like', '%' . $s . '%')
        ->orWhere('kode_barang', 'like', '%' . $s . '%')
        ->orderBy('nama', 'asc')
        ->limit(5);
}
  
  public function haskategori() {
		return $this->hasOne(InventoriKategoriMdl::class, 'id', 'kategori_id');
	}

  public function scopeCmb($q){
    return $q->selectRaw('inventori_barang.kategori_id as id, inventori_kategori.nama as val')
             ->leftJoin('inventori_kategori', 'inventori_barang.kategori_id', '=', 'inventori_kategori.id')
             ->orderBy('inventori_kategori.nama', 'asc')->groupBy('inventori_kategori.nama','inventori_barang.kategori_id');
  }

  public function scopeList($q, $f1, $f2){
    return $q->when($f1, function($w) use ($f1) {
      return $w->where('nama','like','%'.$f1.'%');
    })->when($f2, function($w) use ($f2) {
      return $w->where('kategori_id',$f2);
    })->orderBy('id','asc');
  }

}
