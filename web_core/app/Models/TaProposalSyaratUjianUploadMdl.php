<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaProposalSyaratUjianUploadMdl extends Model {
  protected $table = 'ta_proposal_syarat_ujian_upload';
  protected $primaryKey = 'id';
  protected $fillable = ['kodeta_proposal_id','ta_proposal_syarat_ujian_id','berkas','is_valid','uploaded_at','uploaded_by','validated_at','validated_by'];

  public $timestamps = false;

}
