<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use \App\Models\PenggunaMdl;
use \App\Models\PenggunaRoleMdl;
use \App\Models\RoleAksesMdl;
use \App\Models\PegawaiMdl;
use \App\Models\MahasiswaMdl;
use \App\Models\RoleMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\LoginAttemptMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Pengguna extends Controller {
	
	public function list(Request $req){
		$modul = 'AdministrasiPengguna';
	
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

		$tbl = PenggunaMdl::list($f1,$f2)->paginate($ppg);

		return view('administrasi.pengguna', [
			'tbl'=>$tbl,
			'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
			'cmb'=>[
				'ppg'=>Mylib::ppg(),
				'is_aktif'=>Mylib::is_aktif(),
			],
			'user_ses'=>$user_ses,
					'ctr_path'=>$user_ses['active_app']['link'].'/pengguna',
					'mylib'=>Mylib::class,
			'page_title'=>[
				'icon'=>'fas fa-list',
				'bread'=>'Data Pengguna',
				'links'=>[
				['title'=>'Pengguna','active'=>''],
				['title'=>'Data Pengguna','active'=>'active'],
				],
			],
		]);
	}



	public function form(Request $req){
		$modul = 'AdministrasiPengguna';
		$user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$state = $req->segment(4);

		$fr = ['in0'=>'', 'in1'=>'', 'in2'=>'', 'in3'=>'', 'in4'=>'', 'in5'=>'Y', 'in6'=>'', 'in6nm'=>'', 'in7'=>'', 'in7nm'=>'', 'in8'=>'', 'in9'=>'', 'path'=>'save'];
		$roles = [];
		if ($req->id) {
			$get = PenggunaMdl::getByid($req->id)->first();
			if (!$get) {
				return redirect($user_ses['active_app']['link'].'/pengguna');
			}
			$fr = [
				'in0'=>$get->id,
				'in1'=>$get->username,
				'in2'=>$get->nama,
				'in3'=>$get->email,
				'in4'=>$get->def_password,
				'in5'=>$get->is_aktif,
				'in6'=>$get->pegawai_id,
				'in6nm'=>($get->pegawai_id) ? Mylib::nama_gelar($get->pegawai_nama_depan, $get->pegawai_nama, $get->pegawai_nama_belakang) : '',
				'in7'=>$get->mahasiswa_nim,
				'in7nm'=>($get->mahasiswa_nim) ? $get->mahasiswa_nim.' - '.Mylib::nama_gelar($get->mahasiswa_nama_depan, $get->mahasiswa_nama, $get->mahasiswa_nama_belakang) : '',
				'in8'=>$get->last_login_time,
				'in9'=>$get->last_login_ip,
				'path'=>'update',
			];
			$roles = PenggunaRoleMdl::getByid($get->id)->get();
		}

		return view('administrasi.pengguna_form', [
			'fr'=>$fr,
			'roles'=>$roles,
				'cmb'=>[],
				'state'=>$state,
			'user_ses'=>$user_ses,
				'ctr_path'=>$user_ses['active_app']['link'].'/pengguna',
			'cmb'=>[
				'role'=>RoleMdl::cmb()->get(),
				'unit_kerja'=>UnitKerjaMdl::cmb()->get(),
			],
				'mylib'=>Mylib::class,
			'page_title'=>[
				'icon'=>'fas fa-server',
				'bread'=>'Data Pengguna',
				'links'=>[
					['title'=>'Pengguna','active'=>''],
					['title'=>'Data Pengguna','active'=>''],
					['title'=>'Form','active'=>'active'],
				],
			],
		]);
	}



	public function get(Request $req){
		$id_peg = $req->post('id_peg');
		$id_mhs = $req->post('id_mhs');

		if (($id_peg == '' && $id_mhs == '') || ($id_peg != '' && $id_mhs != '')) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail','custom','Pilih salah satu, Data Pegawai / Data Mahasiswa.')
			]);
		}
	
		if ($id_peg) {
			$get = PegawaiMdl::where('id',$id_peg)->first();
			if ($get) {
				$res = [$get->nip, Mylib::nama_gelar($get->nama_depan, $get->nama, $get->nama_belakang), (($get->email) ? $get->email : $get->email_kampus)];
				return response()->json([
					'status'=>'success',
					'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>$res
				]);
			}
		} else {
			$get = MahasiswaMdl::where('nim',$id_mhs)->first();
			if ($get) {
				$res = [$get->nim, Mylib::nama_gelar($get->nama_depan, $get->nama, $get->nama_belakang), (($get->email) ? $get->email : $get->email_kampus)];
				return response()->json([
					'status'=>'success',
					'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>$res
				]);
			}
		}

		return response()->json([
			'status'=>'info',
			'statusText'=>Mylib::pesan('fail','custom','Data Pegawai / Data Mahasiswa tidak ditemukan.')
		]);
	}


	public function save(Request $req){
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|alpha_num',
			'in2' => 'required|string',
			'in3' => 'nullable|email:rfc,dns',
			'in4' => 'nullable|string',
			'in4t' => 'required|alpha',
			'in5' => 'required|alpha',
			'in6' => 'nullable|numeric',
			'in7' => 'nullable|numeric',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'email' => Mylib::validasi('email'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha_num' => Mylib::validasi('alpha_num'),
			'alpha' => Mylib::validasi('alpha'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Usename',
			'in2' => 'Nama Lengkap',
			'in3' => 'Email',
			'in4' => 'Default Password',
			'in4t' => 'Generate Default Password',
			'in6' => 'Data Pegawai',
			'in7' => 'Data Mahasiswa',
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
		$in4t = $req->post('in4t');
		$in5 = $req->post('in5');
		$in6 = $req->post('in6');
		$in7 = $req->post('in7');
		$dtm = date('Y-m-d H:i:s');

		if ($in4t == 'Y' || $in4 == '') {
			$in4 = Str::password(8, true, true, false, false);
		}

		$cek_username = PenggunaMdl::where('username',$in1)->when($act == 'update', function($w) use ($in0){
			return $w->where('id','!=',$in0);
		})->first();
		if ($cek_username) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail','custom','Username sudah digunakan.')
			]);
		}
		
		try {
			if ($act == 'update') {
				$prep = PenggunaMdl::find($in0);
				if (!$prep) {
					return response()->json([
						'status'=>'info',
						'statusText'=>Mylib::pesan('fail','custom','Data Pengguna tidak ditemukan.')
					]);
				}
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$prep = new PenggunaMdl();
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
				$prep->password = md5($in4);
				$prep->is_def_password = 'Y';
			}
			$prep->username = $in1;
			$prep->nama = $in2;
			$prep->email = $in3; 
			$prep->def_password = $in4; 
			$prep->pegawai_id = ($in6) ? $in6 : NULL; 
			$prep->mahasiswa_nim = ($in7) ? $in7 : NULL; 
			$prep->is_aktif = $in5; 
			$prep->save();
			
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success','save'), 'directto'=>url($user_ses['active_app']['link'].'/pengguna/form/edit/'.$prep->id)
			]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
			]);
		}
	}



	public function delete(Request $req){
		$id = $req->post('id');
		DB::beginTransaction();
		try {
			PenggunaMdl::where('id',$id)->delete();
			PenggunaRoleMdl::where('pengguna_id',$id)->delete();
			DB::commit();
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success','delete')
			]);
		} catch (\Illuminate\Database\QueryException $exception) {
			DB::rollback();
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
			]);
		}
	}



	public function add_role(Request $req){
		$validasi = Validator::make($req->all(), [
			'in0' => 'required|numeric',
			'in1' => 'required|numeric',
			'in2' => 'required|alpha_num',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha_num' => Mylib::validasi('alpha_num'),
		], [
			'in0' => 'ID',
			'in1' => 'Role',
			'in2' => 'Unit Kerja',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $msg)
			]);
		}

		$in0 = $req->post('in0');
		$in1 = $req->post('in1');
		$in2 = $req->post('in2');
	
		$cek = PenggunaRoleMdl::where('pengguna_id',$in0)->where('role_id',$in1)->first();
		if ($cek) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Role sudah ada.')]);
		}
		try {
			$prep = new PenggunaRoleMdl();
			$prep->pengguna_id = $in0;
			$prep->role_id = $in1;
			$prep->unit_kerja_kode = $in2;
			$prep->save();
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success','custom','Role berhasil ditambahkan.')
			]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
			]);
		}
	}



	public function delete_role(Request $req){
		$id = $req->post('id');    
		try {
			$delete = PenggunaRoleMdl::where('id',$id)->delete();
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success','custom','Role berhasil dihapus.')
			]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
			]);
		}
	}



	public function loginas(Request $req){
		$id = $req->post('id');
		$user_ses = $req->session()->get('user_ses');
		if ($user_ses['active_role']['is_super_admin'] != 'Y'){
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail','custom','Maaf, Anda tidak memliki hak akses.')
			]);
		}

		$user = []; $pass = false; $msg = '';
		$getuser = PenggunaMdl::find($id);
		if ($getuser) {
			if ($getuser->is_aktif == 'Y') {
				$roles = [];

				$pengguna_role = PenggunaRoleMdl::selectRaw('pengguna_role.*,r.nama as role_nama, r.is_admin as role_is_admin, r.is_super_admin as role_is_super_admin')
					->leftJoin('role as r', 'pengguna_role.role_id','=','r.id')
					->where('pengguna_role.pengguna_id',$getuser->id)
					->orderBy('r.urutan','asc')
					->get();

				$role_ids = [];
				foreach ($pengguna_role as $pr) {
					$role_ids[] = $pr->role_id;

					$role_akses = RoleAksesMdl::selectRaw('a.id as aplikasi_id, a.nama as aplikasi_nama, a.link as aplikasi_link, a.pict as aplikasi_pict, a.is_external_link as aplikasi_is_external_link')
						->leftJoin('aplikasi_menu as am', 'role_akses.aplikasi_menu_id','=','am.id')
						->leftJoin('aplikasi as a', 'am.aplikasi_id','=','a.id')
						->where('role_akses.role_id', $pr->role_id)
						->where('role_akses.is_view', 'Y')
						->groupBy('a.id')
						->groupBy('a.nama')
						->groupBy('a.link')
						->groupBy('a.pict')
						->groupBy('a.is_external_link')
						->get();
					foreach ($role_akses as $ra) {
						$roles[$ra->aplikasi_id]['app_id'] = $ra->aplikasi_id;
						$roles[$ra->aplikasi_id]['app_nama'] = $ra->aplikasi_nama;
						$roles[$ra->aplikasi_id]['app_link'] = $ra->aplikasi_link;
						$roles[$ra->aplikasi_id]['app_is_external_link'] = $ra->aplikasi_is_external_link;
						$roles[$ra->aplikasi_id]['app_pict'] = $ra->aplikasi_pict;
						$roles[$ra->aplikasi_id]['app_roles'][$pr->role_id] = [
							'role_id'=>$pr->role_id,
							'role_nama'=>$pr->role_nama,
							'role_is_admin'=>$pr->role_is_admin,
							'role_is_super_admin'=>$pr->role_is_super_admin,
							'role_unit_kerja_kode'=>$pr->unit_kerja_kode
						];
					}
				}

				$user = [
					'id'=>$getuser->id,
					'nama'=>$getuser->nama,
					'username'=>$getuser->username,
					'pegawai_id'=>$getuser->pegawai_id,
					'mahasiswa_nim'=>$getuser->mahasiswa_nim,
					'email'=>$getuser->email,
					'pict'=>($getuser->pict) ? $getuser->pict : env('PROFIL_PICT'),
					'is_def_password'=>$getuser->is_def_password,
					'roles'=>$roles,
				];

				$getuser->last_login_ip = $req->ip();
				$getuser->last_login_time = date('Y-m-d H:i:s');
				$getuser->save();
				
				$pass = true;	
			} else {
				$msg = 'Pengguna tidak aktif, hubungi Administrator.';
			}
		} else {
			$msg = 'Username tidak ditemukan.';
		}

		if ($pass === true) {
			$req->session()->put('user_ses', $user);
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success', 'custom', 'Selamat Datang...'), 'toUrl'=>url('/gate')
			]);
		} else {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $msg)
			]);
		}
	}



	public function clear_attempt(Request $req){
		$id = $req->post('id');    
		try {
			$delete = LoginAttemptMdl::where('username',$id)->delete();
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success','custom','Log berhasil dibersihkan.')
			]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
			]);
		}
	}



	public function reset(Request $req){
		$id = $req->post('id');
		try {
			$get = PenggunaMdl::find($id);
			if (!$get) {
				return response()->json([
					'status'=>'info',
					'statusText'=>Mylib::pesan('info','custom','Data Pengguna tidak ditemukan.')
				]);
			}
			$get->password = md5($get->username);
			$get->save();
			return response()->json([
				'status'=>'success',
				'statusText'=>Mylib::pesan('success','custom','Password direset ke default.')
			]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json([
				'status'=>'info',
				'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
			]);
		}
	}



}
