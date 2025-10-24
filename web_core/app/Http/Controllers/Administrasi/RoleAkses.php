<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use \App\Models\AplikasiMdl;
use \App\Models\AplikasiMenuMdl;
use \App\Models\RoleMdl;
use \App\Models\RoleAksesMdl;
use \App\Models\PenggunaRoleMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class RoleAkses extends Controller {
  

  public function list(Request $req){
    $modul = 'AdministrasiRoleAkses';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$filter = $req->post('filter');
		$act = $req->get('act');

    if ($act == 'reset') {
      $f1 = ''; $f2 = '';
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
		}
		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2]);

		$res = [];
		if ($f1 != '' && $f2 != '') {
			$menu = AplikasiMenuMdl::getByid($f1)->get();
			foreach ($menu as $m) {
				$role_akses = RoleAksesMdl::firstOrCreate(['role_id'=>$f2, 'aplikasi_menu_id'=>$m['id']]);
				$res[] = [
					'id'=>$m->id, 'nama'=>$m->nama, 'level'=>$m->level, 'role_akses_id'=>$role_akses->id, 'is_view'=>$role_akses->is_view, 'is_create'=>$role_akses->is_create, 'is_update'=>$role_akses->is_update, 'is_delete'=>$role_akses->is_delete, 'is_verif1'=>$role_akses->is_verif_1, 'is_verif2'=>$role_akses->is_verif_2, 'is_sign'=>$role_akses->is_sign, 'fr_view'=>$m->fr_view, 'fr_create'=>$m->fr_create, 'fr_update'=>$m->fr_update, 'fr_delete'=>$m->fr_delete, 'fr_verif_1'=>$m->fr_verif_1, 'fr_verif_2'=>$m->fr_verif_2, 'fr_sign'=>$m->fr_sign
				];
			}
		}

		return view('administrasi.role_akses_list', [
      'tbl'=>$res,
      'var'=>['f1'=>$f1, 'f2'=>$f2,],
      'cmb'=>[
        'aplikasi'=>AplikasiMdl::cmb()->get(),
        'role'=>($f1) ? RoleMdl::cmb()->get() : [],
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/role-akses',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Role Akses',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Role Akses','active'=>'active'],
        ],
      ],
		]);
  }
	


	public function update(Request $req){
		$validasi = Validator::make($req->all(), [
			'id' => 'required|numeric',
			'act' => 'required|alpha_dash',
			'state' => 'required|alpha',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha' => Mylib::validasi('alpha'),
			'alpha_dash' => Mylib::validasi('alpha_dash'),
		], [
			'id' => 'ID',
			'act' => 'ACT',
			'state' => 'State',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}		
		$user_ses = $req->session()->get('user_ses');
		$id = $req->post('id');
		$act = $req->post('act');
		$state = $req->post('state');
		$dtm = date('Y-m-d H:i:s');
		
		try {
			$update = RoleAksesMdl::where('id',$id)->update(['is_'.$act=>$state]);
			if ($update > 0) {
				return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
			} else {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Gagal mengupdate data.')]);
			}
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}

}
