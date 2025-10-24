<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaDataSyaratUjianUploadMdl extends Model {
  protected $table = 'ta_data_syarat_ujian_upload';
  protected $primaryKey = 'id';
  protected $fillable = ['kodeta_data_id','ta_data_syarat_ujian_id','berkas','is_valid','uploaded_at','uploaded_by','validated_at','validated_by'];

  public $timestamps = false;

}
