<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAksesMdl extends Model {
  protected $table = 'role_akses';
  protected $primaryKey = 'id';
  protected $fillable = ['role_id','aplikasi_menu_id','is_view','is_create','is_update','is_delete','is_verif_1','is_verif_2','is_sign','updated_at','updated_by'];
  public $timestamps = false;
  
}
