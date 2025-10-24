<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\RoleMdl;
use \App\Models\PenggunaRoleMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Role extends Controller {
  

  public function list(Request $req){
    $modul = 'AdministrasiRole';

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

    $tbl = RoleMdl::list($f1,$f2)->paginate($ppg);

		return view('administrasi.role_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/role',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Role',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Role','active'=>'active'],
        ],
      ],
		]);
  }



	public function get(Request $req){
		$id = $req->post('id');
		$get = RoleMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Role tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->nama, $get->urutan, $get->is_aktif, $get->is_admin]]);

	}



  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|string',
			'in2' => 'required|numeric|gt:4',
			'in3' => 'required|alpha',
			'in4' => 'required|alpha',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha' => Mylib::validasi('alpha'),
      'in2.gt' => ':attribute lebih besar dari 4',
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Nama Role',
			'in2' => 'Urutan',
			'in3' => 'Status Aktif',
			'in3' => 'Akses Admin',
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
		$dtm = date('Y-m-d H:i:s');
		
		try {
			if ($act == 'update') {
				$prep = RoleMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Role tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$prep = new RoleMdl();
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
			}
			$prep->nama = $in1;
			$prep->urutan = $in2;
			$prep->is_aktif = $in3;
			$prep->is_admin = $in4;
      $prep->is_edit = 'Y'; 
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }



  public function delete(Request $req){
    $id = $req->post('id');
    $cek = PenggunaRoleMdl::where('role_id',$id)->first();
    if ($cek) {
      return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Tidak bisa menghapus data, Role sudah digunakan.')]);
    }
    try {
      $del = RoleMdl::where('id',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }
}
