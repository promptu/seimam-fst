<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use \App\Models\PeriodeMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class TahunAkademik extends Controller {
  

  public function list(Request $req){
    $modul = 'TahunAkademik';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');

    if ($act == 'reset') {
      $f1 = ''; $f2 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = PeriodeMdl::list()->paginate($ppg);

		return view('administrasi.tahun_akademik_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/tahun-akademik',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Tahun Akademik',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Tahun Akademik','active'=>'active'],
        ],
      ],
		]);
  }



	public function get(Request $req){
		$id = $req->post('id');
		$get = PeriodeMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Periode tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->kode, $get->nama, $get->nama_singkat, $get->is_aktif]]);

	}



	public function save(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in1' => 'required|numeric',
			'in2' => 'required|string',
			'in3' => 'required|string',
			'in4' => 'required|alpha',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha' => Mylib::validasi('alpha'),
		], [
			'act' => 'ACT',
			'in1' => 'Kode',
			'in2' => 'Nama Tahun Akademik',
			'in3' => 'Nama Singkat',
			'in4' => 'Status Aktif',
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
			if ($in4 == 'Y') {
				DB::table('periode')->update(['is_aktif'=>'T']);
			}
			if ($act == 'update') {
				$prep = PeriodeMdl::find($in1);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Tahun Akademik tidak ditemukan.')]); }
			} else {
				$prep = new PeriodeMdl();
				$prep->kode = $in1;
			}
			$prep->nama = $in2;
			$prep->nama_singkat = $in3;
			$prep->is_aktif = $in4;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}	



	public function delete(Request $req){
		$id = $req->post('id');
		try {
		$del = PeriodeMdl::where('kode',$id)->delete();
				return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
		} catch (\Illuminate\Database\QueryException $exception) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
			}
	}
}
