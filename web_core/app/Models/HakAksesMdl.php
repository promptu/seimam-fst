<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HakAksesMdl extends Model {
	protected $table = 'hak_akses';
	protected $primaryKey = 'id';
	protected $fillable = ['aplikasi_id','role_id','menu_id','is_view','is_insert','is_update','is_delete','is_validate','is_approve','is_sign','is_other','created_at','created_by','updated_at','updated_by'];
	public $timestamps = false;
}
