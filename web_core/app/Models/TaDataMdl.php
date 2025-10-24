<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaDataMdl extends Model {
  protected $table = 'ta_data';
  protected $primaryKey = 'id';
  protected $filable = ['ta_jenis_kode','mahasiswa_nim','jenjang_kode','tanggal','topik','topik_en','topik_ar','judul','judul_en','judul_ar','ta_status_pengajuan_kode','ta_status_pengajuan_ket','abstrak','tolak_at','tolak_by','acc_at','acc_by','created_at','created_by','updated_at','updated_by','status_berkas','status_lulus','ta_ruang_ujian_kode','tgl_ujian_mulai','tgl_ujian_selesai','nilai_pembimbing','nilai_penguji','bobot_pembimbing','bobot_penguji','nilai_akhir'];
  
  public $timestamps = false;


  public function scopeList($q, $f1, $f2, $f3, $f4, $f5){
    return $q->selectRaw('ta_data.*, m.gelar_depan as mahasiswa_gelar_depan, m.nama as mahasiswa_nama, m.gelar_belakang as mahasiswa_gelar_belakang, m.program_studi_kode, j.nama as jenjang_nama, taj.nama as ta_jenis_nama, tsp.nama as ta_status_pengajuan_nama, tsp.label as ta_status_pengajuan_label')
      ->leftJoin('mahasiswa as m', 'ta_data.mahasiswa_nim', '=', 'm.nim')
      ->leftJoin('unit_kerja as p', 'm.program_studi_kode','=','p.kode')
      ->leftJoin('jenjang as j', 'ta_data.jenjang_kode','=','j.kode')
      ->leftJoin('ta_jenis as taj', 'ta_data.ta_jenis_kode','=','taj.kode')
      ->leftJoin('ta_status_pengajuan as tsp', 'ta_data.ta_status_pengajuan_kode','=','tsp.kode')
      ->when($f1, function($w) use ($f1){
        return $w->where('ta_data.ta_jenis_kode',$f1);
      })
      ->when($f2, function($w) use ($f2){
        return $w->whereRaw('p.urutan like (select concat(urutan,"%") from unit_kerja where kode=?)', [$f2]);
      })
      ->when($f3, function($w) use ($f3){
        return $w->where('m.periode_id',$f3);
      })
      ->when($f4, function($w) use ($f4){
        return $w->where('ta_data.ta_status_pengajuan_kode',$f4);
      })
      ->when($f5, function($w) use ($f5){
        return $w->whereRaw('(m.nim like ? or m.nama like ?)',['%'.$f5.'%','%'.$f5.'%']);
      })->orderBy('ta_data.id','asc');
  }

  public function scopeGetByid($q, $id){
    return $q->selectRaw('ta_data.*, m.gelar_depan as mahasiswa_gelar_depan, m.nama as mahasiswa_nama, m.gelar_belakang as mahasiswa_gelar_belakang, j.nama as jenjang_nama, taj.nama as ta_jenis_nama, tsp.nama as ta_status_pengajuan_nama, tsp.label as ta_status_pengajuan_label, m.program_studi_kode as prodi_kode, prodi.nama as prodi_nama, m.sks_lulus as mahasiswa_sks_lulus, r.nama as ta_ruang_ujian_nama')
      ->leftJoin('mahasiswa as m', 'ta_data.mahasiswa_nim', '=', 'm.nim')
      ->leftJoin('unit_kerja as prodi', 'm.program_studi_kode','=','prodi.kode')
      ->leftJoin('jenjang as j', 'ta_data.jenjang_kode','=','j.kode')
      ->leftJoin('ta_jenis as taj', 'ta_data.ta_jenis_kode','=','taj.kode')
      ->leftJoin('ta_status_pengajuan as tsp', 'ta_data.ta_status_pengajuan_kode','=','tsp.kode')
      ->leftJoin('ta_ruang_ujian as r','ta_data.ta_ruang_ujian_kode','=','r.kode')
      ->where('ta_data.id',$id);
  }

  public function scopeListMahasiswa($q, $nim){
    return $q->selectRaw('ta_data.*, m.gelar_depan as mahasiswa_gelar_depan, m.nama as mahasiswa_nama, m.gelar_belakang as mahasiswa_gelar_belakang, j.nama as jenjang_nama, taj.nama as ta_jenis_nama, tsp.nama as ta_status_pengajuan_nama, tsp.label as ta_status_pengajuan_label, prodi.nama as prodi_nama')
      ->leftJoin('mahasiswa as m', 'ta_data.mahasiswa_nim', '=', 'm.nim')
      ->leftJoin('unit_kerja as prodi', 'm.program_studi_kode','=','prodi.kode')
      ->leftJoin('jenjang as j', 'ta_data.jenjang_kode','=','j.kode')
      ->leftJoin('ta_jenis as taj', 'ta_data.ta_jenis_kode','=','taj.kode')
      ->leftJoin('ta_status_pengajuan as tsp', 'ta_data.ta_status_pengajuan_kode','=','tsp.kode')
      ->where('ta_data.mahasiswa_nim',$nim);
  }

  public function scopeListStatusBerkas($q, $f1, $f2, $f3, $f4, $f5){
    return $q->selectRaw('ta_data.*, m.gelar_depan as mahasiswa_gelar_depan, m.nama as mahasiswa_nama, m.gelar_belakang as mahasiswa_gelar_belakang, m.program_studi_kode, j.nama as jenjang_nama, taj.nama as ta_jenis_nama, tsp.nama as ta_status_pengajuan_nama, tsp.label as ta_status_pengajuan_label')
      ->leftJoin('mahasiswa as m', 'ta_data.mahasiswa_nim', '=', 'm.nim')
      ->leftJoin('unit_kerja as p', 'm.program_studi_kode','=','p.kode')
      ->leftJoin('jenjang as j', 'ta_data.jenjang_kode','=','j.kode')
      ->leftJoin('ta_jenis as taj', 'ta_data.ta_jenis_kode','=','taj.kode')
      ->leftJoin('ta_status_pengajuan as tsp', 'ta_data.ta_status_pengajuan_kode','=','tsp.kode')
      ->when($f1, function($w) use ($f1){
        return $w->where('ta_data.ta_jenis_kode',$f1);
      })
      ->when($f2, function($w) use ($f2){
        return $w->whereRaw('p.urutan like (select concat(urutan,"%") from unit_kerja where kode=?)', [$f2]);
      })
      ->when($f3, function($w) use ($f3){
        return $w->where('m.periode_id',$f3);
      })
      ->when($f4, function($w) use ($f4){
        return $w->where('ta_data.status_berkas',$f4);
      })
      ->when($f5, function($w) use ($f5){
        return $w->whereRaw('(m.nim like ? or m.nama like ?)',['%'.$f5.'%','%'.$f5.'%']);
      })
      ->orderBy('ta_data.id','asc');
  }

  public function scopeListStatusBerkasValid($q, $f1, $f2, $f3, $f4, $f5){
    return $q->selectRaw('ta_data.*, m.gelar_depan as mahasiswa_gelar_depan, m.nama as mahasiswa_nama, m.gelar_belakang as mahasiswa_gelar_belakang, m.program_studi_kode, j.nama as jenjang_nama, taj.nama as ta_jenis_nama, tsp.nama as ta_status_pengajuan_nama, tsp.label as ta_status_pengajuan_label, r.nama as ta_ruang_ujian_nama')
      ->leftJoin('mahasiswa as m', 'ta_data.mahasiswa_nim', '=', 'm.nim')
      ->leftJoin('unit_kerja as p', 'm.program_studi_kode','=','p.kode')
      ->leftJoin('jenjang as j', 'ta_data.jenjang_kode','=','j.kode')
      ->leftJoin('ta_jenis as taj', 'ta_data.ta_jenis_kode','=','taj.kode')
      ->leftJoin('ta_status_pengajuan as tsp', 'ta_data.ta_status_pengajuan_kode','=','tsp.kode')
      ->leftJoin('ta_ruang_ujian as r','ta_data.ta_ruang_ujian_kode','=','r.kode')
      ->when($f1, function($w) use ($f1){
        return $w->where('ta_data.ta_jenis_kode',$f1);
      })
      ->when($f2, function($w) use ($f2){
        return $w->whereRaw('p.urutan like (select concat(urutan,"%") from unit_kerja where kode=?)', [$f2]);
      })
      ->when($f3, function($w) use ($f3){
        return $w->where('m.periode_id',$f3);
      })
      ->when($f4, function($w) use ($f4){
        return $w->whereRaw('(m.nim like ? or m.nama like ?)',['%'.$f4.'%','%'.$f4.'%']);
      })
      ->where('ta_data.status_berkas', "VALID")
      ->when($f5, function($w) use ($f5){
        if ($f5 == "TERJADWAL") {
          return $w->whereRaw('ta_data.ta_ruang_ujian_kode IS NOT NULL AND ta_data.tgl_ujian_mulai IS NOT NULL AND ta_data.tgl_ujian_selesai IS NOT NULL');
        } elseif ($f5 == "BLANK") {
          return $w->whereRaw('ta_data.ta_ruang_ujian_kode IS NULL AND ta_data.tgl_ujian_mulai IS NULL AND ta_data.tgl_ujian_selesai IS NULL');
        } else {
          return $w->where('ta_data.status_lulus',$f5);
        }
      })
      ->orderBy('ta_data.id','asc');
  }

}
