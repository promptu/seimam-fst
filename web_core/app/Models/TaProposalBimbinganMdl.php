<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaProposalBimbinganMdl extends Model {
  protected $table = 'ta_proposal_bimbingan';
  protected $primaryKey = 'id';
  protected $fillable = ['ta_proposal_id','ta_proposal_pembimbing_id','bimbingan_ke','tgl_bimbingan','topik','lampiran','status_disetujui','catatan_pembimbing','status_bimbingan','created_at','created_by','updated_at','updated_by','bahasan'];
	public $timestamps = false;

  public function scopeListBimbingan($q, $id_proposal, $id_pembimbing){
    return $q->selectRaw('ta_proposal_bimbingan.*, peg.gelar_depan as peg_gelar_depan, peg.nama as peg_nama, peg.gelar_belakang as peg_gelar_belakang, pemb.pembimbing_ke')
      ->leftJoin('ta_proposal as p','ta_proposal_bimbingan.ta_proposal_id','=','p.id')
      ->leftJoin('ta_proposal_pembimbing as pemb','ta_proposal_bimbingan.ta_proposal_pembimbing_id','=','pemb.id')
      ->leftJoin('pegawai as peg','pemb.pegawai_id','=','peg.id')
      ->where('ta_proposal_bimbingan.ta_proposal_id', $id_proposal)
      ->where('pemb.is_active','Y')
      ->when($id_pembimbing, function($w) use ($id_pembimbing){
        return $w->where('ta_proposal_bimbingan.ta_proposal_pembimbing_id', $id_pembimbing);
      });
  }

  public function scopeLastBimbingan($q, $id_proposal, $id_pembimbing){
    return $q->where('ta_proposal_id', $id_proposal)
      ->where('ta_proposal_pembimbing_id', $id_pembimbing)
      ->orderBy('bimbingan_ke','desc');
  }

  public function scopeGetBimbinganByid($q, $id_proposal_bimbingan){
    return $q->selectRaw('ta_proposal_bimbingan.*, peg.gelar_depan as peg_gelar_depan, peg.nama as peg_nama, peg.gelar_belakang as peg_gelar_belakang, pemb.pembimbing_ke, m.nama as mahasiswa_nama, m.gelar_depan as mahasiswa_gelar_depan, m.gelar_belakang as mahasiswa_gelar_belakang, p.topik as proposal_topik, p.judul as proposal_judul')
      ->leftJoin('ta_proposal as p','ta_proposal_bimbingan.ta_proposal_id','=','p.id')
      ->leftJoin('ta_proposal_pembimbing as pemb','ta_proposal_bimbingan.ta_proposal_pembimbing_id','=','pemb.id')
      ->leftJoin('pegawai as peg','pemb.pegawai_id','=','peg.id')
      ->leftJoin('mahasiswa as m', 'p.mahasiswa_nim','=','m.nim')
      ->where('ta_proposal_bimbingan.id', $id_proposal_bimbingan);
  }

  public function scopeListBimbinganByDosen($q, $id_pegawai, $mahasiswa_nim, $status){
    return $q->selectRaw('ta_proposal_bimbingan.*, m.nim as mahasiswa_nim, m.nama as mahasiswa_nama, m.gelar_depan as mahasiswa_gelar_depan, m.gelar_belakang as mahasiswa_gelar_belakang, p.judul, pem.pembimbing_ke')
      ->leftJoin('ta_proposal as p', 'ta_proposal_bimbingan.ta_proposal_id', '=', 'p.id')
      ->leftJoin('mahasiswa as m', 'p.mahasiswa_nim', '=', 'm.nim')
      ->leftJoin('ta_proposal_pembimbing as pem', 'ta_proposal_bimbingan.ta_proposal_pembimbing_id', 'pem.id')
      ->where('pem.pegawai_id', $id_pegawai)
      ->when($mahasiswa_nim, function($w) use ($mahasiswa_nim){
        return $w->where('m.nim', $mahasiswa_nim);
      })
      ->when($status, function($w) use ($status){
        return $w->where('ta_proposal_bimbingan.status_bimbingan', $status);
      })
      ->orderBy('ta_proposal_bimbingan.bimbingan_ke','desc');
  }

  public function scopeListBimbinganKe($q, $proposal_id, $pegawai_id){
    return $q->selectRaw('ta_proposal_bimbingan.id, ta_proposal_bimbingan.bimbingan_ke')
      ->join('ta_proposal_pembimbing as b', 'ta_proposal_bimbingan.ta_proposal_pembimbing_id', '=', 'b.id')
      ->where('ta_proposal_bimbingan.ta_proposal_id', $proposal_id)
      ->where('b.pegawai_id', $pegawai_id)
      ->orderBy('bimbingan_ke','desc');
  }

}
