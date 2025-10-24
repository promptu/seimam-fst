<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use \App\Models\TaDataMdl;
use \App\Models\TaDataPengujiMdl;
use \App\Models\TaDataPembimbingMdl;
use \App\Models\TaRuangUjianMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class DataTaNilai extends Controller {
  
	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function detail(Request $req){
    $modul = 'TaDataNilai';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$ctr_link = $user_ses['active_app']['link'].'/data-ta';
		$action = $req->segment(4);

		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($ctr_link);
		}

		$get = TaDataMdl::getByid($id)->first();
		if (!$get) {
			return redirect($ctr_link);
		}

		return view('ta.data.nilai', [
			'get'=>$get,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$ctr_link,
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
			'cmb'=>[
				'ruang'=> TaRuangUjianMdl::cmb()->get(),
			],
			'action'=>$action,
			'segment_page'=>'nilai_akhir',
			'ta_status'=>$get->ta_status_pengajuan_kode,
			'id_proposal'=>$id,
			'id_page'=>$enc_id,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Nilai Akhir Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Nilai Akhir Proposal','active'=>'active'],
        ],
      ],
		]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function update(Request $req){    
		$validasi = Validator::make($req->all(), [
			'id' => 'required|numeric',
			'penguji' => 'required',
			'pembimbing' => 'required',
			'bobot_penguji' => 'required|min:1|max:100',
			'bobot_pembimbing' => 'required|min:1|max:100',
			'bobot_penguji' => 'required|numeric',
			'bobot_pembimbing' => 'required|numeric',
			'status_lulus' => 'required|alpha'
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha' => Mylib::validasi('alpha'),
			// 'array' => ':attribute bukan array.',
			'min' => ':attribute minimal :min.',
			'max' => ':attribute maksimal :max.',
		], [
			'id' => 'ID Proposal',
			'penguji' => 'Penguji',
			'pembimbing' => 'Pembimbing',
			'bobot_penguji' => 'Bobot Penguji',
			'bobot_pembimbing' => 'Bobot Pembimbing',
			'status_lulus' => 'Status Lulus',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}
		$id = $req->post('id');
		$penguji = json_decode($req->post('penguji'),true);
		$pembimbing = json_decode($req->post('pembimbing'),true);
		$bobot_penguji = $req->post('bobot_penguji');
		$bobot_pembimbing = $req->post('bobot_pembimbing');
		$nilai_penguji = $req->post('nilai_penguji');
		$nilai_pembimbing = $req->post('nilai_pembimbing');
		$status_lulus = $req->post('status_lulus');
		$user_ses = $req->session()->get('user_ses');
		$dtm = date('Y-m-d H:i:s');
		$user_id = $user_ses['id'];

		DB::beginTransaction();
		try {
			foreach ($penguji as $key => $value) {
				TaDataPengujiMdl::where('id', $value['id'])->update(['nilai_angka'=>$value['val']]);
			}
			foreach ($pembimbing as $key => $value) {
				TaDataPembimbingMdl::where('id', $value['id'])->update(['nilai_angka'=>$value['val']]);
			}
			$prep = TaDataMdl::find($id);
			if (!$prep) {
				DB::rollBack();
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data proposal tidak ditemukan.')]);
			}
			$prep->nilai_pembimbing = $nilai_pembimbing;
			$prep->nilai_penguji = $nilai_penguji;
			$prep->bobot_pembimbing = $bobot_pembimbing;
			$prep->bobot_penguji = $bobot_penguji;
			$prep->nilai_akhir = (($bobot_penguji/100)*$nilai_penguji) + (($bobot_pembimbing/100)*$nilai_pembimbing);
			$prep->status_lulus = $status_lulus;
			$prep->save();
			DB::commit();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','update')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			DB::rollBack();
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}		
  }

}
