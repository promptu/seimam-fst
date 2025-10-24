<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerjaJabatanMdl extends Model {
	protected $table = 'unit_kerja_jabatan';
	protected $primaryKey = 'id';
	protected $fillable = ['unit_kerja_kode','nama','nama_singkat','parent_id','urutan','level','is_aktif','created_at','created_by','updated_at','updated_by'];
	public $timestamps = false;

	public function scopeList($q, $f1, $f2, $f3){
		return $q->selectRaw('unit_kerja_jabatan.*, u.nama as unit_kerja_nama, u.level as unit_kerja_level, p.nama as parent_nama')
			->leftJoin('unit_kerja as u','unit_kerja_jabatan.unit_kerja_kode','=','u.kode')
			->leftJoin('unit_kerja_jabatan as p','unit_kerja_jabatan.parent_id','=','p.id')
			->when($f1, function($w) use ($f1){
				return $w->whereRaw('u.urutan like (select concat(urutan, "%") from unit_kerja where kode=?)', [$f1]);
			})->when($f2, function($w) use ($f2){
				return $w->whereRaw('(unit_kerja_jabatan.nama like ? or unit_kerja_jabatan.nama_singkat like ?)', ['%'.$f2.'%','%'.$f2.'%']);
			})->when($f3, function($w) use ($f3){
				return $w->where('unit_kerja_jabatan.is_aktif', $f3);
			})
			->orderBy('u.urutan','asc')
			->orderBy('unit_kerja_jabatan.urutan','asc');
	}

	public function scopeCmbParent($q, $id){
		return $q->selectRaw('id, nama as val, level')
			->where('unit_kerja_kode', $id)
			->where('is_aktif','Y')->where('level','<=',2)
			->orderBy('urutan','asc');
	}

	public function scopeCmb($q, $id){
		return $q->selectRaw('unit_kerja_jabatan.id, unit_kerja_jabatan.nama as val, unit_kerja_jabatan.level')
      ->leftJoin('unit_kerja as u','unit_kerja_jabatan.unit_kerja_kode','=','u.kode')
			->when($id, function($w) use ($id){
				return $w->whereRaw('u.urutan like (select concat(urutan, "%") from unit_kerja where kode=?)', [$id]);
			})
			->where('unit_kerja_jabatan.is_aktif','Y')
			->orderBy('unit_kerja_jabatan.urutan','asc');
	}

}
