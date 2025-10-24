<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangMdl;
use App\Models\InventoriBarangMasuklMdl;
use App\Models\RoleMdl;
use App\Models\UnitKerjaMdl;


class InventoriBarangMasukDetailMdl extends Model {
  protected $table = 'inventori_barang_masuk_detail';
  protected $primaryKey = 'id';
  protected $fillable = ['barang_masuk_id','barang_id','jumlah','status','created_at','updated_at'];


  public function scopeGetByid($q, $id, $f1=null, $f2=null){
    return $q->selectRaw('inventori_barang_masuk_detail.*, inventori_barang.nama as nama_barang, inventori_barang.satuan as satuan, inventori_status_barang_masuk.nama as status_detail, inventori_status_barang_masuk.label as status_detail_label')
            ->leftJoin('inventori_barang_masuk', 'inventori_barang_masuk_detail.barang_masuk_id', '=', 'inventori_barang_masuk.id')
            ->leftJoin('inventori_barang', 'inventori_barang.id', '=', 'inventori_barang_masuk_detail.barang_id')
            ->leftJoin('inventori_status_barang_masuk', 'inventori_barang_masuk_detail.status', '=', 'inventori_status_barang_masuk.kode')
            ->leftJoin('unit_kerja', 'inventori_barang_masuk.unit_id', '=', 'unit_kerja.kode')
            ->leftJoin('role', 'inventori_barang_masuk.pengguna_role', '=', 'role.id')
    ->where('inventori_barang_masuk.id',$id)
     ->when($f1, function($w) use ($f1){
      return $w->where('unit_kerja.nama','like','%'.$f1.'%');
})
      ->when($f2, function($w) use ($f2){
        return $w->where('inventori_barang_masuk.status_ajuan',$f2);
      })->orderBy('inventori_barang_masuk_detail.created_at','desc');;
  }

  public function scopeNamaBarang($q){
    return $q->selectRaw('inventori_barang.id as id, inventori_barang.nama as val')
             ->leftJoin('inventori_barang', 'inventori_barang.id', '=', 'inventori_barang_masuk_detail.barang_id');
              }

public function has_barang(){
		return $this->hasOne(InventoriBarangMdl::class, 'id', 'barang_id');
	}
public function has_barangmasuk(){
		return $this->hasOne(InventoriBarangMasukMdl::class, 'id', 'barang_masuk_id');
	}
public function has_statusbarang(){
		return $this->hasOne(InventoriStatusBarangMasukMdl::class, 'kode', 'status');
	}

}
