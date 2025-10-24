<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiMdl extends Model {
  protected $table = 'pegawai';
  protected $primaryKey = 'id';
  protected $fillable = ['id','nip','nama','nidn','nup','nidk','nik','gelar_depan','gelar_belakang','jenis_kelamin','agama_id','negara_kode','tanggal_lahir','tempat_lahir','alamat','kecamatan_kode','kota_kode','provinsi_kode','nomor_hp','email','email_kampus','status_aktif_pegawai_kode','status_kepegawaian_kode','fungsional_kode','is_dosen','unit_kerja_kode','unit_kerja_jabatan_id'];
  public $timestamps = false;
  public $incrementing = false;

	public function scopeList($q, $f1, $f2, $f3, $f4, $f5){
		return $q->selectRaw('pegawai.*, fungsional.nama as fungsional_nama, unit_kerja.nama as unit_kerja_nama, unit_kerja.level as unit_kerja_level, status_aktif_pegawai.nama as status_aktif_pegawai_nama')
			->leftJoin('agama','pegawai.agama_id','=','agama.id')
			->leftJoin('unit_kerja','pegawai.unit_kerja_kode','=','unit_kerja.kode')
			->leftJoin('fungsional','pegawai.fungsional_kode','=','fungsional.kode')
			->leftJoin('status_aktif_pegawai','pegawai.status_aktif_pegawai_kode','=','status_aktif_pegawai.kode')
			->when($f1, function($w) use ($f1){
				return $w->whereRaw('unit_kerja.urutan like (select concat(urutan,"%") from unit_kerja where kode=?)', [$f1]);
			})
			->when($f2, function($w) use ($f2){
				return $w->where('pegawai.nama', 'like', '%'.$f2.'%');
			})
      ->when($f3, function($w) use ($f3){
        return $w->where('pegawai.status_aktif_kode',$f3);
      })
      ->when($f4, function($w) use ($f4){
        return $w->where('pegawai.fungsional_kode',$f4);
      })
      ->when($f5, function($w) use ($f5){
        return $w->where('pegawai.unit_kerja_jabatan_id',$f5);
      })
			->orderBy('unit_kerja.urutan','asc')->orderBy('pegawai.nama','asc');
	}

	public function scopeSelCmb($q, $s){
		return $q->where('nip','like','%'.$s.'%')
			->orWhere('nama','like','%'.$s.'%')
			->orderBy('nip','asc')->limit(5);
	}

	public function scopeGetByid($q, $id){
		return $q->selectRaw('pegawai.*, fungsional.nama as fungsional_nama, unit_kerja.nama as unit_kerja_nama, unit_kerja.level as unit_kerja_level, status_aktif_pegawai.nama as status_aktif_pegawai_nama, status_kepegawaian.nama as status_kepegawaian_nama')
			->leftJoin('agama','pegawai.agama_id','=','agama.id')
			->leftJoin('unit_kerja','pegawai.unit_kerja_kode','=','unit_kerja.kode')
			->leftJoin('fungsional','pegawai.fungsional_kode','=','fungsional.kode')
			->leftJoin('status_aktif_pegawai','pegawai.status_aktif_pegawai_kode','=','status_aktif_pegawai.kode')
			->leftJoin('status_kepegawaian','pegawai.status_kepegawaian_kode','=','status_kepegawaian.kode')
			->where('pegawai.id',$id);

	}

	public function scopeSelCmbDosen($q, $s){
		return $q->whereRaw('(nip like ? or nama like ?)', ['%'.$s.'%','%'.$s.'%'])
			// ->where('fungsional_kode','P1')
			->orderBy('nip','asc')->limit(5);

	}
}
