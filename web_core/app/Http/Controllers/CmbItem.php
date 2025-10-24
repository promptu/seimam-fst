<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\PegawaiMdl;
use \App\Models\MahasiswaMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class CmbItem extends Controller {

	public function cmb_pegawai(Request $req){
		$qw = $req->get('q');
    $res = [];
		$get = PegawaiMdl::selCmb($qw)->get();
    foreach ($get as $c) {
      $res[] = [
        'id'=>$c->id,
        'text'=>Mylib::nama_gelar($c->gelar_depan, $c->nama, $c->gelar_belakang),
      ];
    }
		return response()->json(['status'=>'success', 'statusText'=>'loaded', 'results'=>$res]);
	}

  public function cmb_mahasiswa(Request $req){
		$qw = $req->get('q');
    $res = [];
		$get = MahasiswaMdl::selCmb($qw)->get();
    foreach ($get as $c) {
      $res[] = [
        'id'=>$c->nim,
        'text'=>$c->nim.' - '.Mylib::nama_gelar($c->gelar_depan, $c->nama, $c->gelar_belakang),
      ];
    }
		return response()->json(['status'=>'success', 'statusText'=>'loaded', 'results'=>$res]);
  }

}
