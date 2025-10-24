<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLogMdl extends Model {
    protected $table = 'login_log';
    protected $primaryKey = 'id';
    protected $fillable = ['pengguna_id','pegawai_id','mahasiswa_nim','role_id','login_time','login_ip'];
    public $timestamps = false;
}
