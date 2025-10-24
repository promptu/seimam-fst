<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\TaRuangUjianMdl;
use \App\Models\TaProposalMdl;
use \App\Models\UnitKerjaMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class RuangUjian extends Controller {
  

  public function list(Request $req){
    $modul = 'TaRuangUjian';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$f3 = $req->post('f3');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');

    if ($act == 'reset') {
      $f1 = ''; $f2 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$f3 = (isset($ctr_ses['f3'])) ? $ctr_ses['f3'] : '00';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

		$arr_var = ['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'ppg'=>$ppg, 'lastno'=>$lastno];

		$req->session()->put($modul, $arr_var);

    $tbl = TaRuangUjianMdl::list($f1,$f2,$f3)->paginate($ppg);

		return view('ta.ruang_ujian_list', [
      'tbl'=>$tbl,
      'var'=>$arr_var,
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'is_aktif'=>Mylib::is_aktif(),
				'unit'=>UnitKerjaMdl::cmb()->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/ruang-ujian',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Ruang Ujian',
        'links'=>[
          ['title'=>'Pengaturan','active'=>''],
          ['title'=>'Ruang Ujian','active'=>'active'],
        ],
      ],
		]);
  }



	public function get(Request $req){
		$id = $req->post('id');
		$get = TaRuangUjianMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Ruangan tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->kode, $get->nama, $get->is_aktif, $get->unit_kerja_kode]]);
	}



  public function save(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in1' => 'required|alpha_num',
			'in2' => 'required|string',
			'in3' => 'required|alpha',
			'in4' => 'required|alpha_num',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'alpha' => Mylib::validasi('alpha'),
		], [
			'act' => 'ACT',
			'in1' => 'Kode',
			'in2' => 'Nama Ruang',
			'in3' => 'Status Aktif',
			'in4' => 'Unit Kerja',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}		
		$user_ses = $req->session()->get('user_ses');
		$act = $req->post('act');
		$in1 = $req->post('in1');
		$in2 = $req->post('in2');
		$in3 = $req->post('in3');
		$in4 = $req->post('in4');
		$dtm = date('Y-m-d H:i:s');
		
		try {
			if ($act == 'update') {
				$prep = TaRuangUjianMdl::find($in1);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Ruangan tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$prep = new TaRuangUjianMdl();
				$prep->kode = $in1;
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
			}
			$prep->nama = $in2;
			$prep->is_aktif = $in3;
			$prep->unit_kerja_kode = $in4;
			$prep->save();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }



  public function delete(Request $req){
    $id = $req->post('id');
    $cek = TaProposalMdl::where('ta_ruang_ujian_kode',$id)->first();
    if ($cek) {
      return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Tidak bisa menghapus data, Ruangan sudah digunakan.')]);
    }
    try {
      $del = TaRuangUjianMdl::where('kode',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }
}
