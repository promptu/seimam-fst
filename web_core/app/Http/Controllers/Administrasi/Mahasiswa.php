<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

use \App\Models\MahasiswaMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\AgamaMdl;
use \App\Models\StatusMahasiswaMdl;
use \App\Models\DataWilayahMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Mahasiswa extends Controller {
  

  public function list(Request $req){
    $modul = 'AdministrasiMahasiswa';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$f3 = $req->post('f3');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');
		$page = $req->get('page');

    if ($act == 'reset') {
      $f1 = ''; $f2 = ''; $f3 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$f3 = (isset($ctr_ses['f3'])) ? $ctr_ses['f3'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'ppg'=>$ppg, 'page'=>$page]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = MahasiswaMdl::list($f1,$f2,$f3)->paginate($ppg);

		return view('administrasi.mahasiswa_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'status_aktif'=>StatusMahasiswaMdl::cmb()->get(),
				'unit_kerja'=>UnitKerjaMdl::cmbAkademik()->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/mahasiswa',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Mahasiswa',
        'links'=>[
          ['title'=>'Sivitas Akademik','active'=>''],
          ['title'=>'Mahasiswa','active'=>'active'],
        ],
      ],
		]);
  }



	public function detail(Request $req){
		$id = $req->id;
    $modul = 'AdministrasiMahasiswa';
		$ctr_ses = $req->session()->get($modul);
    $user_ses = $req->session()->get('user_ses');

		$get = MahasiswaMdl::getByid($id)->first();
		if (!$get) {
			return redirect($user_ses['active_app']['link'].'/mahasiswa');
		}
		$ctr_path = $user_ses['active_app']['link'].'/mahasiswa';
		return view('administrasi.mahasiswa_detail', [
			'get'=>$get,
      'user_ses'=>$user_ses,
			'ctr_path'=>$ctr_path,
			'back_path'=>$ctr_path.(($ctr_ses['page'] > 1) ? '?page='.$ctr_ses['page'] : ''),
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-database',
        'bread'=>'Detail Mahasiswa',
        'links'=>[
          ['title'=>'Sivitas Akademik','active'=>''],
          ['title'=>'Detail Mahasiswa','active'=>'active'],
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
		])->timeout(3)->get(env('SEVIMA_URL').'/mahasiswa/'.$id);
		
		if ($http->ok()) {
			$r = $http->json();
			
			$curdata = $r['attributes'];
			$cur_unit = $curdata['id_program_studi'];
			$cur_id = $r['id'];
			try {
        
        if ($curdata['id_agama']) {
          $agama_insert = AgamaMdl::updateOrCreate(['id'=>$curdata['id_agama']], ['nama'=>$curdata['agama']]);
        }

        if ($curdata['id_status_mahasiswa']) {
          $status_mahasiswa_insert = StatusMahasiswaMdl::updateOrCreate(['kode'=>$curdata['id_status_mahasiswa']], ['nama'=>$curdata['status_mahasiswa']]);
        }

        if ($curdata['id_kecamatan']) {
          $status_mahasiswa_insert = DataWilayahMdl::updateOrCreate(['kode'=>$curdata['id_kecamatan']], ['nama'=>$curdata['kecamatan']]);
        }

        if ($curdata['id_kota']) {
          $status_mahasiswa_insert = DataWilayahMdl::updateOrCreate(['kode'=>$curdata['id_kota']], ['nama'=>$curdata['kota']]);
        }
				
				$insert = MahasiswaMdl::where('nim',$cur_id)->update([							
						'nama'=>($curdata['nama']) ? $curdata['nama'] : NULL,
						'nisn'=>($curdata['nisn']) ? $curdata['nisn'] : NULL,
						'npsn'=>($curdata['npsn']) ? $curdata['npsn'] : NULL,
						'periode_id'=>($curdata['id_periode']) ? $curdata['id_periode'] : NULL,
            'periode_terakhir_id'=>($curdata['id_periode_terakhir']) ? $curdata['id_periode_terakhir'] : NULL,
            'agama_id'=>($curdata['id_agama']) ? $curdata['id_agama'] : NULL,
            'program_studi_kode'=>($curdata['id_program_studi']) ? $curdata['id_program_studi'] : NULL,
            'jenjang_kode'=>($curdata['id_jenjang']) ? $curdata['id_jenjang'] : NULL,
            'status_mahasiswa_kode'=>($curdata['id_status_mahasiswa']) ? $curdata['id_status_mahasiswa'] : NULL,
            'nik'=>($curdata['nik']) ? $curdata['nik'] : NULL,
            'nama'=>$curdata['nama'],
            'gelar_depan'=>($curdata['gelar_depan']) ? $curdata['gelar_depan'] : NULL,
            'gelar_belakang'=>($curdata['gelar_belakang']) ? $curdata['gelar_belakang'] : NULL,
            'kecamatan_kode'=>($curdata['id_kecamatan']) ? $curdata['id_kecamatan'] : NULL,
            'kota_kode'=>($curdata['id_kota']) ? $curdata['id_kota'] : NULL,
            // 'provinsi_kode'=>($curdata['']) ? $curdata[''] : NULL,
            // 'negara_kode'=>($curdata['']) ? $curdata[''] : NULL,
            'tempat_lahir'=>($curdata['tempat_lahir']) ? $curdata['tempat_lahir'] : NULL,
            'tanggal_lahir'=>($curdata['tanggal_lahir']) ? $curdata['tanggal_lahir'] : NULL,
            'jenis_kelamin'=>$curdata['jenis_kelamin'],
            'email'=>($curdata['email']) ? $curdata['email'] : NULL,
            'email_kampus'=>($curdata['email_kampus']) ? $curdata['email_kampus'] : NULL,
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

		$endmsg = ($success_count > 0) ? 'Berhasil memperbarui '.$success_count.' data.<br>' : '';
		$endmsg .= ($error_count > 0) ? 'Gagal memperbarui '.$error_count.' data.<br>' : '';
		$endmsg .= ($msg != '') ? $msg : '';
		return response()->json(['status'=>'success', 'statusText'=>$endmsg]);
	}


}
