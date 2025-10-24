<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AplikasiMdl;
use App\Models\RoleAksesMdl;
use App\Models\PenggunaRoleMdl;
use App\Models\LoginLogMdl;

use App\Library\Mylib;

class Gate extends Controller {
	
	// gate view
	public function view(Request $req){
		$user_ses = $req->session()->get('user_ses');
		return view('gate', [
			'user_ses'=>$user_ses,
			'ctr'=> ['link'=>'/gate'],
			'page_title'=>[
				'icon'=>'fas fa-user-edit',
				'bread'=>'Daftar',
				'links'=>[
					['title'=>'Daftar','active'=>'active'],
				],
			],
		]);
	}



	// choose modul
	public function choose(Request $req){
		$validasi = Validator::make($req->all(), [
			'aid'=>'required|numeric',
			'rid'=>'required|numeric',
		]);
		if ($validasi->fails()) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Instruksi tidak lengkap.')]);
		}

		$aplikasi_id = $req->post('aid');
		$role_id = $req->post('rid');
		$user_ses = $req->session()->get('user_ses');

		if (!isset($user_ses['roles'][$aplikasi_id])) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Aplikasi ID tidak valid.')]);
		}
		$aplikasi_ses = $user_ses['roles'][$aplikasi_id];

		if (!isset($aplikasi_ses['app_roles'][$role_id])) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Pengguna tidak memiliki hak akses.')]);
		}
		$role_ses = $aplikasi_ses['app_roles'][$role_id];

		$toUrl = ($aplikasi_ses['app_is_external_link'] == 'Y') ? $aplikasi_ses['app_link'] : url($aplikasi_ses['app_link']);

		$req->session()->put('user_ses.active_role', ['id'=>$role_id, 'nama'=>$role_ses['role_nama'], 'is_admin'=>$role_ses['role_is_admin'], 'is_super_admin'=>$role_ses['role_is_super_admin'], 'unit_kerja_kode'=>$role_ses['role_unit_kerja_kode'], 'unit_kerja_nama'=>$role_ses['role_unit_kerja_nama']]);
		$req->session()->put('user_ses.active_app', ['id'=>$aplikasi_id,'nama'=>$aplikasi_ses['app_nama'], 'link'=>$aplikasi_ses['app_link']]);
		$req->session()->put('user_ses.menu', $this->generate_menu($aplikasi_id, $role_id, $user_ses['id']));
		
		LoginLogMdl::create([
			'pengguna_id'=>$user_ses['id'],
			'pegawai_id'=>$user_ses['pegawai_id'],
			'mahasiswa_nim'=>$user_ses['mahasiswa_nim'],
			'role_id'=>$role_id,
			'login_time'=>date('Y-m-d H:i:s'),
			'login_ip'=>$req->ip(),
		]);

		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success', 'custom', 'Selamat Datang.'), 'toUrl'=>$toUrl]);
	}



	// generate menu
	private function generate_menu($aplikasi_id, $role_id, $user_id){
		
		if ($user_id == 0) {
			$qmenu = DB::table('aplikasi_menu as am')->selectRaw('am.*')
			->where('am.is_aktif','Y')
			->where('am.aplikasi_id',$aplikasi_id)      
			->orderBy('am.urutan','asc')
			->get();
		} else {
			$qmenu = DB::table('aplikasi_menu as am')->selectRaw('am.*')
			->leftJoin('role_akses as ara','am.id','=','ara.aplikasi_menu_id')
			->where('am.is_aktif','Y')
			->where('ara.role_id',$role_id)
			->where('am.aplikasi_id',$aplikasi_id)      
			->where('ara.is_view','Y')      
			->orderBy('am.urutan','asc')
			->get();
		}
		$menu = json_decode($qmenu, true);

		$no = 0; $cur_level = ''; $res_navbar = ''; $res_sidebar = '';

		foreach ($menu as $key => $r) {
			if (++$no > 1) {
				if ($r['level'] < $cur_level) {
					$res_navbar .= str_repeat('</ul></li>', ($cur_level - $r['level']));
					$res_sidebar .= str_repeat('</ul></li>', ($cur_level - $r['level']));
				}
			}
			if ($r['is_separator'] == 'Y') {
				$res_navbar .= '<li class="nav-item"><hr class="my-0"></li>';
				$cur_level = $r['level'];
			} else {
				$has_sub = (isset($menu[$key + 1]) && $menu[$key + 1]['level'] > $r['level']) ? true : false;

				if ($has_sub) {
					if ($r['level'] == 1) {
						$res_navbar .= '<li class="nav-item dropdown">';
						$res_navbar .= '<a id="m-'.$r['id'].'" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">'.$r['nama'].'</a>';
						$res_navbar .= '<ul aria-labelledby="m-'.$r['id'].'" class="dropdown-menu">';
					} else {
						$res_navbar .= '<li class="dropdown-submenu dropdown-hover">';
						$res_navbar .= '<a id="sm-'.$r['id'].'" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">'.$r['nama'].'</a>';
						$res_navbar .= '<ul aria-labelledby="sm-'.$r['id'].'" class="dropdown-menu shadow">';
					}
					$res_sidebar .= '<li class="nav-item">';
					$res_sidebar .= '<a href="#" class="nav-link '.(($r['level'] >= 2) ? 'pl-4' : '').'"><p>'.$r['nama'].' <i class="right fas fa-angle-left nav-width"></i></p></a><ul class="nav nav-treeview">';
				} else {
					if ($r['level'] == 1) {
						$res_navbar .= '<li class="nav-item"><a href="'.url($r['link']).'" class="nav-link">'.$r['nama'].'</a></li>';
					} else {
						$res_navbar .= '<li><a href="'.url($r['link']).'" class="dropdown-item">'.$r['nama'].'</a></li>';
					}
					$res_sidebar .= '<li class="nav-item"><a href="'.url($r['link']).'" class="nav-link '.(($r['level'] == 3) ? 'pl-4 ml-4' : (($r['level'] == 2) ? 'pl-4' : '')).'"><p>'.$r['nama'].'</p></a></li>';
				}
				$cur_level = $r['level'];
			}
		}
		$res_navbar .= ($cur_level == 3) ? '</ul></li></ul></li>' : (($cur_level == 2) ? '</ul></li>' : '');
		$res_sidebar .= ($cur_level == 3) ? '</ul></li></ul></li>' : (($cur_level == 2) ? '</ul></li>' : '');
		return ['navbar'=>$res_navbar, 'sidebar'=>$res_sidebar];
	}

}
