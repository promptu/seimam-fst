<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakPengajuanSyaratMdl extends Model {
	protected $table = 'bak_pengajuan_syarat';
	protected $primaryKey = 'id';
	protected $fillable = ['id', 'bak_pengajuan_id', 'bak_syarat_id','berkas','is_valid	', 'validated_at', 'validated_by','validated_msg'];
	public $timestamps = false;

	public function scopeByIdPengajuan($q, $id){
		return $q->selectRaw('bak_pengajuan_syarat.*')
			->leftJoin('bak_syarat', 'bak_pengajuan_syarat.bak_syarat_id', '=', 'bak_syarat.id')
			->where('bak_pengajuan_syarat.bak_pengajuan_id', $id)
			->orderBy('bak_syarat.nama','asc');
	}
}
