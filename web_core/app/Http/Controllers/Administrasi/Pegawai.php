<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

use \App\Models\PegawaiMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\UnitKerjaJabatanMdl;
use \App\Models\AgamaMdl;
use \App\Models\StatusAktifPegawaiMdl;
use \App\Models\StatusKepegawaianMdl;
use \App\Models\FungsionalMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Pegawai extends Controller {
  

  public function list(Request $req){
    $modul = 'AdministrasiPegawai';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$f3 = $req->post('f3');
		$f4 = $req->post('f4');
		$f5 = $req->post('f5');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');
		$page = $req->get('page');

    if ($act == 'reset') {
      $f1 = ''; $f2 = ''; $f3 = ''; $f4 = ''; $f5 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$f3 = (isset($ctr_ses['f3'])) ? $ctr_ses['f3'] : '';
			$f4 = (isset($ctr_ses['f4'])) ? $ctr_ses['f4'] : '';
			$f5 = (isset($ctr_ses['f5'])) ? $ctr_ses['f5'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'f4'=>$f4, 'f5'=>$f5, 'ppg'=>$ppg, 'page'=>$page]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = PegawaiMdl::list($f1,$f2,$f3,$f4,$f5)->paginate($ppg);

		return view('administrasi.pegawai_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'f4'=>$f4, 'f5'=>$f5, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'status_aktif'=>StatusAktifPegawaiMdl::cmb()->get(),
				'unit_kerja'=>UnitKerjaMdl::cmb()->get(),
				'fungsional'=>FungsionalMdl::cmb()->get(),
				'unit_kerja_jabatan'=>UnitKerjaJabatanMdl::cmb($f1)->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/pegawai',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Pegawai',
        'links'=>[
          ['title'=>'Sivitas Akademik','active'=>''],
          ['title'=>'Pegawai','active'=>'active'],
        ],
      ],
		]);
  }



	public function pull_batch(Request $req){
		$request_for = ($req->post('request_for') == 'dosen') ? 'dosen' : 'pegawai';
		$arr_unit = [];
		$get_unit = UnitKerjaMdl::cmb()->get();
		foreach ($get_unit as $r) {
			$arr_unit[] = $r->id;
		}

		$cur_page = 0; $loop = true;
		$success_count = 0; $error_count = 0;  $msg = '';

		while ($loop == true) {
			$http = Http::withHeaders([
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				'X-App-Key' => env('X_APP_KEY'),
				'X-Secret-Key' => env('X_SECRET_KEY'),
			])->timeout(3)->get(env('SEVIMA_URL').'/'.$request_for.'?page='.++$cur_page);
			
			if ($http->ok()) {
				$res_body = $http->json();
				$meta = $res_body['meta'];
				$last_page = $meta['last_page'];

				if ($cur_page == $last_page) {
					$loop = false;
				}
	
				$data = $res_body['data'];
	
				foreach ($data as $r) {
					$curdata = $r['attributes'];
					$cur_unit = $curdata['id_satuan_kerja'];
					$cur_id = $r['id'];
					// if (in_array($cur_unit, $arr_unit)) {
						try {						
							if (isset($curdata['id_satuan_kerja']) && $curdata['id_satuan_kerja'] != "") {
								$agama_insert = UnitKerjaMdl::updateOrCreate(['kode'=>$curdata['id_satuan_kerja']], ['nama'=>$curdata['satuan_kerja']]);
							}
							if (isset($curdata['id_agama']) && $curdata['id_agama'] != "") {
								$agama_insert = AgamaMdl::updateOrCreate(['id'=>$curdata['id_agama']], ['nama'=>$curdata['agama']]);
							}
							if (isset($curdata['id_status_aktif']) && $curdata['id_status_aktif'] != "") {
								$status_aktif = StatusAktifPegawaiMdl::updateOrCreate(['kode'=>$curdata['id_status_aktif']], ['nama'=>$curdata['status_aktif']]);
							}
							if (isset($curdata['id_status_kepegawaian']) && $curdata['id_status_kepegawaian'] != "") {
								$status_kepeg = StatusKepegawaianMdl::updateOrCreate(['kode'=>$curdata['id_status_kepegawaian']], ['nama'=>$curdata['status_kepegawaian']]);
							}
							if (isset($curdata['id_fungsional']) && $curdata['id_fungsional'] != "") {
								$fungsional = FungsionalMdl::updateOrCreate(['kode'=>$curdata['id_fungsional']], ['nama'=>$curdata['fungsional']]);
							}
							$pegawai_id = (isset($curdata['id_pegawai'])) ? $curdata['id_pegawai'] : NULL;
							$insert = PegawaiMdl::updateOrCreate( ['id'=>$pegawai_id],
								[
									'nip'=>(isset($curdata['nip'])) ? $curdata['nip'] : NULL,
									'nama'=>(isset($curdata['nama'])) ? $curdata['nama'] : NULL,
									'nidn'=>(isset($curdata['nidn'])) ? $curdata['nidn'] : NULL,
									'nup'=>(isset($curdata['nupn'])) ? $curdata['nupn'] : NULL,
									'nidk'=>(isset($curdata['nidk'])) ? $curdata['nidk'] : NULL,
									'nik'=>NULL,
									'gelar_depan'=>(isset($curdata['gelar_depan'])) ? $curdata['gelar_depan'] : NULL,
									'gelar_belakang'=>(isset($curdata['gelar_belakang'])) ? $curdata['gelar_belakang'] : NULL,
									'jenis_kelamin'=>(isset($curdata['jenis_kelamin'])) ? $curdata['jenis_kelamin'] : NULL,
									'agama_id'=>(isset($curdata['id_agama'])) ? $curdata['id_agama'] : NULL,
									'negara_kode'=>NULL,
									'tanggal_lahir'=>(isset($curdata['tanggal_lahir']) && $curdata['tanggal_lahir'] != "") ? $curdata['tanggal_lahir'] : NULL,
									'tempat_lahir'=>(isset($curdata['tempat_lahir'])) ? $curdata['tempat_lahir'] : NULL,
									'alamat'=>(isset($curdata['alamat'])) ? $curdata['alamat'] : NULL,
									'kecamatan_kode'=>NULL,
									'kota_kode'=>NULL,
									'provinsi_kode'=>NULL,
									'nomor_hp'=>(isset($curdata['nomor_hp'])) ? $curdata['nomor_hp'] : NULL,
									'email'=>(isset($curdata['email'])) ? $curdata['email'] : NULL,
									'email_kampus'=>(isset($curdata['email_kampus'])) ? $curdata['email_kampus'] : NULL,
									'status_aktif_pegawai_kode'=>(isset($curdata['id_status_aktif'])) ? $curdata['id_status_aktif'] : NULL,
									'status_kepegawaian_kode'=>(isset($curdata['id_status_kepegawaian'])) ? $curdata['id_status_kepegawaian'] : NULL,
									'fungsional_kode'=> (isset($curdata['id_fungsional'])) ? $curdata['id_fungsional'] : NULL,
									'is_dosen'=>'T',
									'unit_kerja_kode'=>$cur_unit,
								]
							);
							++$success_count;
						} catch (\Illuminate\Database\QueryException $exception) {
							$msg .=  $exception->errorInfo[2].'<br>';
							++$error_count;
						}
					// }	
				}
			} else {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Gagal menghubungkan ke API Server pada halaman '.$cur_page.'.')]);
			}
		}

		$endmsg = ($success_count > 0) ? 'Berhasil memperbarui '.$success_count.' data. ' : '';
		$endmsg .= ($error_count > 0) ? 'Gagal memperbarui '.$error_count.' data. ' : '';
		$endmsg .= ($msg != '') ? $msg : '';
		return response()->json(['status'=>'success', 'statusText'=>$endmsg]);
	}



	public function detail(Request $req){
		$id = $req->id;
    $modul = 'AdministrasiPegawai';
		$ctr_ses = $req->session()->get($modul);
    $user_ses = $req->session()->get('user_ses');

		$get = PegawaiMdl::getByid($id)->first();
		if (!$get) {
			return redirect($user_ses['active_app']['link'].'/pegawai');
		}
		$ctr_path = $user_ses['active_app']['link'].'/pegawai';
		return view('administrasi.pegawai_detail', [
			'get'=>$get,
      'user_ses'=>$user_ses,
			'ctr_path'=>$ctr_path,
			'back_path'=>$ctr_path.(($ctr_ses['page'] > 1) ? '?page='.$ctr_ses['page'] : ''),
      'cmb'=>[
				'unit_kerja_jabatan'=>UnitKerjaJabatanMdl::cmb('')->get(),
      ],
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-database',
        'bread'=>'Detail Pegawai',
        'links'=>[
          ['title'=>'Sivitas Akademik','active'=>''],
          ['title'=>'Detail Pegawai','active'=>'active'],
        ],
      ],
		]);
	}


	
	public function pull(Request $req){
		$id = $req->post('id');
		if ($id == '') {
			return respose()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Gagal memperbarui data, ID tidak ada.')]);
		}

		$success_count = 0; $error_count = 0;  $msg = '';

		$http = Http::withHeaders([
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
			'X-App-Key' => env('X_APP_KEY'),
			'X-Secret-Key' => env('X_SECRET_KEY'),
		])->timeout(3)->get(env('SEVIMA_URL').'/pegawai/'.$id);
		
		if ($http->ok()) {
			$r = $http->json();
			
			$curdata = $r['attributes'];
			$cur_unit = $curdata['id_satuan_kerja'];
			$cur_id = $r['id'];
			try {						
				if ($curdata['id_agama']) {
					$agama_insert = AgamaMdl::updateOrCreate(['id'=>$curdata['id_agama']], ['nama'=>$curdata['agama']]);
				}
				if ($curdata['id_status_aktif']) {
					$status_aktif = StatusAktifPegawaiMdl::updateOrCreate(['kode'=>$curdata['id_status_aktif']], ['nama'=>$curdata['status_aktif']]);
				}
				if ($curdata['id_status_kepegawaian']) {
					$status_kepeg = StatusKepegawaianMdl::updateOrCreate(['kode'=>$curdata['id_status_kepegawaian']], ['nama'=>$curdata['status_kepegawaian']]);
				}
				if ($curdata['id_fungsional']) {
					$fungsional = FungsionalMdl::updateOrCreate(['kode'=>$curdata['id_fungsional']], ['nama'=>$curdata['fungsional']]);
				}
				
				$insert = PegawaiMdl::updateOrCreate( ['id'=>$cur_id],
					[							
						'nip'=>($curdata['nip']) ? $curdata['nip'] : NULL,
						'nama'=>($curdata['nama']) ? $curdata['nama'] : NULL,
						'nidn'=>($curdata['nidn']) ? $curdata['nidn'] : NULL,
						'nup'=>($curdata['nupn']) ? $curdata['nupn'] : NULL,
						'nidk'=>($curdata['nidk']) ? $curdata['nidk'] : NULL,
						'nik'=>NULL,
						'gelar_depan'=>($curdata['gelar_depan']) ? $curdata['gelar_depan'] : NULL,
						'gelar_belakang'=>($curdata['gelar_belakang']) ? $curdata['gelar_belakang'] : NULL,
						'jenis_kelamin'=>($curdata['jenis_kelamin']) ? $curdata['jenis_kelamin'] : NULL,
						'agama_id'=>($curdata['id_agama']) ? $curdata['id_agama'] : NULL,
						'negara_kode'=>NULL,
						'tanggal_lahir'=>($curdata['tanggal_lahir']) ? $curdata['tanggal_lahir'] : NULL,
						'tempat_lahir'=>($curdata['tempat_lahir']) ? $curdata['tempat_lahir'] : NULL,
						'alamat'=>($curdata['alamat']) ? $curdata['alamat'] : NULL,
						'kecamatan_kode'=>NULL,
						'kota_kode'=>NULL,
						'provinsi_kode'=>NULL,
						'nomor_hp'=>($curdata['nomor_hp']) ? $curdata['nomor_hp'] : NULL,
						'email'=>($curdata['email']) ? $curdata['email'] : NULL,
						'email_kampus'=>($curdata['email_kampus']) ? $curdata['email_kampus'] : NULL,
						'status_aktif_pegawai_kode'=>($curdata['id_status_aktif']) ? $curdata['id_status_aktif'] : NULL,
						'status_kepegawaian_kode'=>($curdata['id_status_kepegawaian']) ? $curdata['id_status_kepegawaian'] : NULL,
						'fungsional_kode'=> ($curdata['id_fungsional']) ? $curdata['id_fungsional'] : NULL,
						'is_dosen'=>'T',
						'unit_kerja_kode'=>$cur_unit,
					]
				);
				++$success_count;
			} catch (\Illuminate\Database\QueryException $exception) {
				$msg .=  $exception->errorInfo[2].'<br>';
				++$error_count;
			}
				
		} else {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Gagal menghubungkan ke API Server.')]);
		}

		$endmsg = ($success_count > 0) ? 'Berhasil memperbarui '.$success_count.' data. ' : '';
		$endmsg .= ($error_count > 0) ? 'Gagal memperbarui '.$error_count.' data. ' : '';
		$endmsg .= ($msg != '') ? $msg : '';
		return response()->json(['status'=>'success', 'statusText'=>$endmsg]);
	}


  
	public function update(Request $req){
		$validasi = Validator::make($req->all(), [
			'id' => 'required|numeric',
			'in1' => 'nullable|numeric',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
		], [
			'id' => 'ID',
			'in1' => 'Jabatan Struktural',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}

		$user_ses = $req->session()->get('user_ses');
		$id = $req->post('id');
		$in1 = $req->post('in1');
    
		try {
      $prep = PegawaiMdl::find($id);
      if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Pengguna tidak ditemukan.')]); }
      $prep->unit_kerja_jabatan_id = $in1;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','update')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}


}
