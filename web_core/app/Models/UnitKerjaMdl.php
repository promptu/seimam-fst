<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerjaMdl extends Model {
  protected $table = 'unit_kerja';
  protected $primaryKey = 'kode';
  protected $fillable = ['kode','nama','nama_singkat','parent_kode','urutan','jenis','level','is_akademik','is_aktif'];
  public $timestamps = false;
  public $incrementing = false;

  public function scopeList($q, $f1, $f2){
    return $q->when($f1, function($w) use ($f1){
      return $w->where('nama','like','%'.$f1.'%');
    })->when($f2, function($w) use ($f2){
      return $w->where('is_aktif',$f2);
    })->orderBy('urutan','asc');
  }

	public function scopeGetbyId($q, $id){
		return $q->selectRaw('unit_kerja.*, parent.nama as parent_nama')
		->leftJoin('unit_kerja as parent', 'unit_kerja.parent_kode','=','parent.kode')
		->where('unit_kerja.kode',$id);
	}

	public function scopeCmb($q){
		return $q->selectRaw('kode as id, nama as val, level')
			->where('is_aktif', 'Y')
			->orderBy('urutan','asc');
	}

	public function scopeCmbAkademik($q){
		return $q->selectRaw('kode as id, nama as val, level')
			->where('is_aktif', 'Y')
			->where('is_akademik', 'Y')
			->orderBy('urutan','asc');
	}
  
	public function scopeCmbProdi($q){
		return $q->selectRaw('kode as id, nama as val, level')
			->where('is_aktif', 'Y')
			->where('jenis', 'prodi')
			->orderBy('urutan','asc');
	}
  
	public function scopeCmbAkademikByid($q, $unit_id){
		return $q->selectRaw('kode as id, nama as val, level')
			->where('is_aktif', 'Y')
			// ->where('is_akademik', 'Y')
			->when($unit_id, function($w) use ($unit_id){
				return $w->whereRaw('urutan like (select concat(urutan,"%") from unit_kerja where kode=?)', [$unit_id]);
			})
			->orderBy('urutan','asc');
	}

}
