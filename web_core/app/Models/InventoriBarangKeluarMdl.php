<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InventoriBarangMdl;
use App\Models\InventoriBarangKeluarDetailMdl;
use App\Models\RoleMdl;
use App\Models\UnitKerjaMdl;
use App\Models\PenggunaMdl;
use App\Models\InventoriStatusPengajuanMdl;


class InventoriBarangKeluarMdl extends Model {
  protected $table = 'inventori_barang_keluar';
  protected $primaryKey = 'id';
  protected $fillable = ['unit_id', 'pengguna_role','pegawai_id','status_ajuan','verif_by','tanggal_ajuan','tanggal_verifikasi','updated_at'];

  
  // Definisikan relasi ke tabel unit_kerja
  public function unitKerja() {
    return $this->belongsTo(UnitKerjaMdl::class, 'unit_id', 'kode');
}

// Definisikan relasi ke tabel unit_kerja
public function has_status_pengajuan() {
  return $this->belongsTo(InventoriStatusPengajuanMdl::class, 'status_ajuan', 'kode');
}

public function scopeList($q, $f1, $f2){
  return $q->selectRaw('inventori_barang_keluar.*, unit_kerja.nama as nama_unit, role.nama as role_pengguna, verifikator.nama as nama_verifikator, pengguna.nama as nama_pegawai, inventori_status_pengajuan.nama as status_pengajuan, inventori_status_pengajuan.label as status_pengajuan_label')
  ->leftJoin('unit_kerja', 'inventori_barang_keluar.unit_id', '=', 'unit_kerja.kode')
  ->leftJoin('role', 'inventori_barang_keluar.pengguna_role', '=', 'role.id')
  ->leftJoin('pengguna', 'inventori_barang_keluar.pegawai_id', '=', 'pengguna.id')
  ->leftJoin('pengguna as verifikator', 'inventori_barang_keluar.verif_by', '=', 'verifikator.id')
  ->leftJoin('inventori_status_pengajuan', 'inventori_barang_keluar.status_ajuan', '=', 'inventori_status_pengajuan.kode')
  ->when($f1, function($w) use ($f1){
    return $w->where('unit_kerja.nama','like','%'.$f1.'%');
  })
  ->when($f2, function($w) use ($f2) {
    return $w->where('inventori_barang_keluar.status_ajuan',$f2);
  })
  ->orderBy('inventori_barang_keluar.tanggal_ajuan','desc');
}



}