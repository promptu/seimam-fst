<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaStatusPengajuanMdl extends Model {
  protected $table = 'ta_status_pengajuan';
  protected $primaryKey = 'kode';
  
  public function scopeCmb($q){
    return $q->selectRaw('kode as id, nama as val')->where('is_aktif','Y')->orderBy('urutan','asc');
  }

  public function scopeCmbForm($q, $in_form = 'all'){
    return $q->selectRaw('kode as id, nama as val')
      ->when($in_form == 'Y' || $in_form == 'T', function($w) use ($in_form) {
        return $w->where('in_form',$in_form);
      })
      ->where('is_aktif','Y')->orderBy('urutan','asc');
  }

}
