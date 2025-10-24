<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangMdl;
use App\Models\InventoriBarangMasukDetailMdl;
use App\Models\RoleMdl;
use App\Models\UnitKerjaMdl;


class InventoriBarangMasukMdl extends Model {
  protected $table = 'inventori_barang_masuk';
  protected $primaryKey = 'id';
  protected $fillable = ['unit_id','pengguna_role','input_by','tanggal_input','tanggal_update'];

  public function scopeCmb($q){
    return $q->selectRaw('inventori_barang_masuk.unit_id as id, unit_kerja.nama as val')
    ->leftJoin('unit_kerja', 'inventori_barang_masuk.unit_id', '=', 'unit_kerja.kode');
       
  }

  // Definisikan relasi ke tabel unit_kerja
  public function unitKerja() {
    return $this->belongsTo(UnitKerjaMdl::class, 'unit_id', 'kode');
}


  public function scopeList($q, $f1, $f2){
    return $q->selectRaw('inventori_barang_masuk.*, unit_kerja.nama as nama_unit, role.nama as pengguna_role, pengguna.nama as diinput_oleh')
    ->leftJoin('unit_kerja', 'inventori_barang_masuk.unit_id', '=', 'unit_kerja.kode')
    ->leftJoin('pengguna', 'inventori_barang_masuk.input_by', '=', 'pengguna.id')
    ->leftJoin('role', 'inventori_barang_masuk.pengguna_role', '=', 'role.id')
    ->when($f1, function($w) use ($f1){
      return $w->where('unit_kerja.nama','like','%'.$f1.'%');
    })
    ->when($f2, function($w) use ($f2) {
      return $w->where('role.nama',$f2);
    })
    ->orderBy('inventori_barang_masuk.tanggal_input','desc');
  }

}