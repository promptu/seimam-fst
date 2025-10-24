<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\UnitKerjaMdl;
use \App\Models\UnitKerjaJabatanMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class UnitKerjaJabatan extends Controller {
  

  public function list(Request $req){
    $modul = 'AdministrasiUnitKerjaJabatan';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$f3 = $req->post('f3');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');

    if ($act == 'reset') {
      $f1 = ''; $f2 = ''; $f3 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$f3 = (isset($ctr_ses['f3'])) ? $ctr_ses['f3'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = UnitKerjaJabatanMdl::list($f1,$f2,$f3)->paginate($ppg);

		return view('administrasi.unit_kerja_jabatan_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'is_aktif'=>Mylib::is_aktif(),
				'unit_kerja'=>UnitKerjaMdl::cmb()->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/jabatan',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Jabatan Struktural',
        'links'=>[
          ['title'=>'Unit & Jabatan','active'=>''],
          ['title'=>'Jabatan Struktural','active'=>'active'],
        ],
      ],
		]);
  }



	public function get(Request $req){
		$id = $req->post('id');
		$get = UnitKerjaJabatanMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Unit Kerja tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->nama, $get->nama_singkat, $get->unit_kerja_kode, $get->parent_id, $get->urutan, $get->level, $get->is_aktif]]);

	}



	public function cmb_jabatan(Request $req){
		$id = $req->post('id');
		$get = UnitKerjaJabatanMdl::cmbParent($id)->get();
		$res = [];
		foreach ($get as $r) {
			$res[] = ['id'=>$r->id, 'val'=>Mylib::tree_view($r->val, $r->level)];
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>$res]);
	}



	public function save(Request $req){
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|string',
			'in2' => 'required|string',
			'in3' => 'required|alpha_num',
			'in4' => 'nullable|numeric',
			'in5' => 'required|numeric',
			'in6' => 'required|numeric',
			'in7' => 'required|alpha',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha_num' => Mylib::validasi('alpha_num'),
			'alpha' => Mylib::validasi('alpha'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Nama Jabatan',
			'in2' => 'Nama Singkat',
			'in3' => 'Unit Kerja',
			'in4' => 'Parent Jabatan',
			'in5' => 'Urutan',
			'in6' => 'Level',
			'in7' => 'Status Aktif',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}		
		$user_ses = $req->session()->get('user_ses');
		$act = $req->post('act');
		$in0 = $req->post('in0');
		$in1 = $req->post('in1');
		$in2 = $req->post('in2');
		$in3 = $req->post('in3');
		$in4 = $req->post('in4');
		$in5 = $req->post('in5');
		$in6 = $req->post('in6');
		$in7 = $req->post('in7');
		$dtm = date('Y-m-d H:i:s');
		
		try {
			if ($act == 'update') {
				$prep = UnitKerjaJabatanMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Jabatan tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$prep = new UnitKerjaJabatanMdl();
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
			}
			$prep->nama = $in1;
			$prep->nama_singkat = $in2;
			$prep->unit_kerja_kode = $in3; 
			$prep->parent_id = $in4; 
			$prep->urutan = $in5; 
			$prep->level = $in6; 
			$prep->is_aktif = $in7; 
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}



	public function delete(Request $req){
		$id = $req->post('id');
		$cek = UnitKerjaJabatanMdl::where('parent_id',$id)->first();
		if ($cek) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Data tidak bisa dihapus karena digunakan sebagai Parent Jabatan.')]);
		}
		try {
			UnitKerjaJabatanMdl::where('id',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}


}
