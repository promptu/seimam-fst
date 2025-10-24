<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangMdl;
use App\Models\InventoriBarangKeluarlMdl;
use App\Models\InventoriStatusPengajuanMdl;
use App\Models\InventoriStatusPengajuanDetailMdl;
use App\Models\RoleMdl;
use App\Models\UnitKerjaMdl;


class InventoriBarangKeluarDetailMdl extends Model {
  protected $table = 'inventori_barang_keluar_detail';
  protected $primaryKey = 'id';
  protected $fillable = ['barang_keluar_id','barang_id','jumlah','jumlah_disetujui','status','tanggal_ajuan','tanggal_verifikasi','created_at','updated_at'];


  public function scopeGetByid($q, $id,  $f1 = null, $f2 = null){
    return $q->selectRaw('inventori_barang_keluar_detail.*, inventori_barang.nama as nama_barang, inventori_barang.satuan as satuan, inventori_status_pengajuan_detail.nama as status_pengajuan, inventori_status_pengajuan_detail.label as status_pengajuan_label, inventori_barang_keluar.status_ajuan as status_pengajuan')
    ->leftJoin('inventori_barang_keluar', 'inventori_barang_keluar_detail.barang_keluar_id', '=', 'inventori_barang_keluar.id')
    ->leftJoin('inventori_barang', 'inventori_barang.id', '=', 'inventori_barang_keluar_detail.barang_id')
    ->leftJoin('inventori_status_pengajuan', 'inventori_barang_keluar.status_ajuan', '=', 'inventori_status_pengajuan.kode')
    ->leftJoin('inventori_status_pengajuan_detail', 'inventori_barang_keluar_detail.status', '=', 'inventori_status_pengajuan_detail.kode')
    ->leftJoin('unit_kerja', 'inventori_barang_keluar.unit_id', '=', 'unit_kerja.kode')
    ->leftJoin('role', 'inventori_barang_keluar.pengguna_role', '=', 'role.id')
    ->where('inventori_barang_keluar.id',$id)
    ->when($f1, function($w) use ($f1){
      return $w->where('unit_kerja.nama','like','%'.$f1.'%');
})
      ->when($f2, function($w) use ($f2){
        return $w->where('inventori_barang_keluar.status_ajuan',$f2);
      }) ->orderByRaw("
      CASE 
          WHEN inventori_barang_keluar_detail.status = 'draft' THEN 1
          WHEN inventori_barang_keluar_detail.status = 'diajukan' THEN 2
          WHEN inventori_barang_keluar_detail.status = 'ditolak' THEN 3
          WHEN inventori_barang_keluar_detail.status = 'disetujui' THEN 4
          ELSE 5
      END
  ")
      ->orderBy('inventori_barang_keluar_detail.tanggal_ajuan','desc');;
  }


  public function scopeNamaBarang($q){
    return $q->selectRaw('inventori_barang.id as id, inventori_barang.nama as val')
             ->leftJoin('inventori_barang', 'inventori_barang.id', '=', 'inventori_barang_keluar_detail.barang_id');
              }

public function has_barang(){
		return $this->hasOne(InventoriBarangMdl::class, 'id', 'barang_id');
	}
public function has_barangkeluar(){
		return $this->hasOne(InventoriBarangKeluarMdl::class, 'id', 'barang_keluar_id');
	}
public function has_statuspengajuan(){
		return $this->hasOne(InventoriStatusPengajuanDetailMdl::class, 'kode', 'status');
	}

  public function has_unitkerja()
{
    return $this->hasOne(UnitKerjaMdl::class, 'kode', 'unit_id');
}

}
