<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use \App\Models\TaJenisMdl;
use \App\Models\TaProposalMdl;
use \App\Models\TaProposalPembimbingMdl;
use \App\Models\TaProposalBimbinganMdl;
use \App\Models\TaStatusPengajuanMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\PeriodeMdl;
use \App\Models\MahasiswaMdl;
use \App\Models\PegawaiMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Topik extends Controller {
  
	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function list(Request $req){
    $modul = 'TaTopik';

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
    if (!$f2) {
      $f2 = $user_ses['active_role']['unit_kerja_kode'];
    }

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

		$arr_page = ['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'f4'=>$f4, 'f5'=>$f5, 'ppg'=>$ppg, 'lastno'=>$lastno, 'page'=>$page];
		$req->session()->put($modul, $arr_page);

    if ($user_ses['active_role']['id'] == '3') {
      $tbl = TaProposalMdl::listMahasiswa($user_ses['mahasiswa_nim'])->paginate($ppg);
    } else {
      $tbl = TaProposalMdl::list($f1,$f2, $f3, $f4, $f5)->paginate($ppg);
    }

		return view('ta.topik_list', [
      'tbl'=>$tbl,
      'var'=>$arr_page,
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'ta_jenis'=>TaJenisMdl::cmbJenis()->get(),
        'prodi'=>UnitKerjaMdl::cmbAkademikByid($user_ses['active_role']['unit_kerja_kode'])->get(),
        'periode'=>PeriodeMdl::cmb()->get(),
        'status_pengajuan'=>TaStatusPengajuanMdl::cmb()->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/proposal',
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Pengajuan Topik',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Daftar Proposal','active'=>'active'],
        ],
      ],
		]);
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function form(Request $req){
    $modul = 'TaProposal';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$state = $req->state;
		$enc_id = $req->id;
		$id = '';

		if ($state != 'add') {
			try {
				$id = Crypt::decryptString($enc_id);
			} catch (DecryptException $th) {
				return redirect($user_ses['active_app']['link'].'/proposal');
			}
		}

		if (!in_array($state, ['add','edit','detail'])) {
			return redirect($user_ses['active_app']['link'].'/proposal');
		}

    $mahasiswa = ['nim'=>'', 'nama'=>''];
    if ($user_ses['active_role']['id'] == '3') {
      $get_mahasiswa = MahasiswaMdl::where('nim',$user_ses['mahasiswa_nim'])->first();
      if ($get_mahasiswa) {
        $mahasiswa = ['nim'=>$get_mahasiswa->nim, 'nama'=>$get_mahasiswa->nim.' - '.Mylib::nama_gelar($get_mahasiswa->gelar_depan, $get_mahasiswa->nama, $get_mahasiswa->belakang)];
      }
    }
		$state_page = 'new';
		$proposal_status = '';
		if ($state == 'add') {
			$fr = ['in0'=>'', 'in1'=>$mahasiswa['nim'], 'in1nm'=>$mahasiswa['nama'], 'in2'=>date('Y-m-d'), 'in3'=>'', 'in3en'=>'', 'in3ar'=>'', 'in4'=>'', 'in4en'=>'', 'in4ar'=>'', 'in5'=>'pengajuan', 'in6'=>'', 'path'=>'save'];
			$get = [];
			$pembimbing = [];
		} elseif ($state == 'edit' || $state == 'detail') {
			if ($id) {
				$get = TaProposalMdl::getByid($id)->first();
				if (!$get) {
					return redirect($user_ses['active_app']['link'].'/proposal');
				}
				$fr = [
					'in0'=>$get->id,
					'in1'=>$get->mahasiswa_nim,
					'in1nm'=>($get->mahasiswa_nim) ? $get->mahasiswa_nim.' - '.Mylib::nama_gelar($get->mahasiswa_gelar_depan, $get->mahasiswa_nama, $get->mahasiswa_gelar_belakang) : '',
					'in2'=>$get->tanggal,
					'in3'=>$get->topik,
					'in3en'=>$get->topik_en,
					'in3ar'=>$get->topik_ar,
					'in4'=>$get->judul,
					'in4en'=>$get->judul_en,
					'in4ar'=>$get->judul_ar,
					'in5'=>$get->ta_status_pengajuan_kode,
					'in6'=>$get->abstrak,
					'path'=>'update',
				];
				$state_page = 'update';
				$proposal_status = $get->ta_status_pengajuan_kode;
				$pembimbing = TaProposalPembimbingMdl::joinAll($id)->get();
			} else {
				return redirect($user_ses['active_app']['link'].'/proposal');
			}
		} else {
			return redirect($user_ses['active_app']['link'].'/proposal');
		}
		
		return view('ta.proposal_form', [
			'fr'=>$fr,
			'get'=>$get,
			'pembimbing'=>$pembimbing,
			'state'=>$state,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$user_ses['active_app']['link'].'/proposal',
			'back_path'=>$user_ses['active_app']['link'].'/proposal'.(($ctr_ses['page']) ? '?page='.$ctr_ses['page'] : ''), 
      'cmb'=>[
        'status_pengajuan'=>TaStatusPengajuanMdl::cmbForm('all')->get(),
      ],
			'segment_page'=>'detail',
			'state_page'=>$state_page,
			'proposal_status'=>$proposal_status,
			'id_page'=>$enc_id,
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-server',
        'bread'=>'Detail Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Data Proposal','active'=>''],
          ['title'=>'Form','active'=>'active'],
        ],
			],
		]);
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function cmb_mahasiswa(Request $req){
		$qw = $req->get('q');
    $res = [];
		$get = MahasiswaMdl::selCmb($qw)->get();
    foreach ($get as $c) {
      $res[] = [
        'id'=>$c->nim,
        'text'=>$c->nim.' - '.Mylib::nama_gelar($c->gelar_depan, $c->nama, $c->gelar_belakang),
      ];
    }
		return response()->json(['status'=>'success', 'statusText'=>'loaded', 'results'=>$res]);
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function cmb_dosen(Request $req){
		$qw = $req->get('q');
    $res = [];
		$get = PegawaiMdl::selCmbDosen($qw)->get();
    foreach ($get as $c) {
      $res[] = [
        'id'=>$c->id,
        'text'=>$c->nip.' - '.Mylib::nama_gelar($c->gelar_depan, $c->nama, $c->gelar_belakang),
      ];
    }
		return response()->json(['status'=>'success', 'statusText'=>'loaded', 'results'=>$res]);
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|numeric',
			'in2' => 'required|date_format:Y-m-d',
			'in3' => 'required|string',
			'in3en' => 'nullable|string',
			'in3ar' => 'nullable|string',
			'in4' => 'required|string',
			'in4en' => 'nullable|string',
			'in4ar' => 'nullable|string',
			'in5' => 'required|alpha_num',
			'in6' => 'required|string',
		], [
			'required' => Mylib::validasi('required'),
			'alpha' => Mylib::validasi('alpha'),
			'numeric' => Mylib::validasi('numeric'),
			'string' => Mylib::validasi('string'),
			'alpha_num' => Mylib::validasi('alpha_num'),
			'date_format' => Mylib::validasi('date_format'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Mahasiswa',
			'in2' => 'Tanggal Pengajuan',
			'in3' => 'Topik',
			'in3en' => 'Topik En.',
			'in3ar' => 'Topik Ar.',
			'in4' => 'Judul',
			'in4en' => 'Judul En.',
			'in4ar' => 'Judul Ar.',
			'in5' => 'Status Pengajuan',
			'in6' => 'Abstrak',
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
		$in3en = $req->post('in3en');
		$in3ar = $req->post('in3ar');
		$in4 = $req->post('in4');
		$in4en = $req->post('in4en');
		$in4ar = $req->post('in4ar');
		$in5 = $req->post('in5');
		$in6 = $req->post('in6');
		$dtm = date('Y-m-d H:i:s');

    $cek_exist = TaProposalMdl::where('ta_status_pengajuan_kode', '!=', 'ditolak')->where('mahasiswa_nim',$in1)
      ->when($act == 'update', function($w) use ($in0){
        return $w->where('id','!=',$in0);
      })->first();
    if ($cek_exist) {
			$msg = 'Pengajuan sudah tidak bisa dilakukan.';
			if ($cek_exist->ta_status_pengajuan_kode == 'pengajuan') {
				$msg = 'Pengajuan sebelumnya masih dalam proses.';
			} elseif ($cek_exist->ta_status_pengajuan_kode == 'disetujui') {
				$msg = 'Pengajuan sebelumnya sudah disetujui.';
			}
      return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom',$msg)]);
    }

    $mahasiswa = MahasiswaMdl::where('nim',$in1)->first();
    if (!$mahasiswa) {
      return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Mahasiswa tidak ditemukan.')]);
    }
    $ta_jenis = TaJenisMdl::where('jenjang_kode',$mahasiswa->jenjang_kode)->first();
    if (!$ta_jenis) {
      return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Jenis Tugas Akhir tidak ditemukan.')]);
    }

		$skripsi_found = 1;
		// if ($act == 'save') {			
		// 	$http = Http::withHeaders([
		// 		'Content-Type' => 'application/json',
		// 		'Accept' => 'application/json',
		// 		'X-App-Key' => env('X_APP_KEY'),
		// 		'X-Secret-Key' => env('X_SECRET_KEY'),
		// 	])->timeout(3)->get(env('SEVIMA_URL').'/mahasiswa/'.$in1.'/krs');
			
		// 	if (!$http->ok()) {
    //     return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Tidak bisa memverifikasi krs mahasiswa ke SIAKAD.')]);
    //   } else {
		// 		$body = $http->json();
    //     $datas = $body['data'];
		// 		foreach ($datas as $data) {
		// 			$attr = $data['attributes'];
		// 			if (in_array($attr['mata_kuliah'], ['Skripsi','skripsi'])) {
		// 				if ($attr['is_krs_diajukan'] == 1 && $attr['is_krs_disetujui'] == 1) { ++$skripsi_found; }
		// 			}
		// 		}
		// 	}
		// }
		
		try {
			if ($act == 'update') {
				$prep = TaProposalMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Pengajuan tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				if ($skripsi_found == 0) {
					return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Mahasiswa belum memiliki KRS Skripsi / Pengajuan KRS Skripsi belum disetujui, silahkan cek KRS di SIAKAD.')]);
				}
				$prep = new TaProposalMdl();
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
			}
      $prep->ta_jenis_kode = $ta_jenis->kode;
			$prep->mahasiswa_nim = $in1;
			$prep->jenjang_kode = $mahasiswa->jenjang_kode;
			$prep->tanggal = $in2;
			$prep->topik = $in3;
			$prep->topik_en = $in3en;
			$prep->topik_ar = $in3ar;
			$prep->judul = $in4;
			$prep->judul_en = $in4en;
			$prep->judul_ar = $in4ar;
			$prep->ta_status_pengajuan_kode = $in5;
			$prep->abstrak = $in6;
			$prep->save();

			$enc_id = Crypt::encryptString($prep->id);
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save'), 'directto'=>url($user_ses['active_app']['link'].'/proposal/form/detail/'.$enc_id)]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function tolak(Request $req){
		$validasi = Validator::make($req->all(), [
			'id'=>'required|numeric',
			'ket'=>'required|string',
		], ['id'=>'ID harus ada.', 'ket'=>'Keterangan harus diisi.']);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}
		$get = TaProposalMdl::find($req->post('id'));
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Data pengajuan tidak ditemukan.')]);
		}
    $user_ses = $req->session()->get('user_ses');

		$get->ta_status_pengajuan_kode = 'ditolak';
		$get->ta_status_pengajuan_ket = $req->post('ket');
		$get->tolak_at = date('Y-m-d H:i:s');
		$get->tolak_by = $user_ses['id'];
		$get->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pengajuan berhasil diupdate.'), 'directto'=>url($user_ses['active_app']['link'].'/proposal/form/detail/'.$req->post('id'))]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function acc(Request $req){
		$validasi = Validator::make($req->all(), ['id'=>'required|numeric'], ['id'=>'ID harus ada.']);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}
		$get = TaProposalMdl::find($req->post('id'));
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Data pengajuan tidak ditemukan.')]);
		}
    $user_ses = $req->session()->get('user_ses');

		$get->ta_status_pengajuan_kode = 'disetujui';
		$get->acc_at = date('Y-m-d H:i:s');
		$get->acc_by = $user_ses['id'];
		$get->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pengajuan berhasil diupdate.'), 'directto'=>url($user_ses['active_app']['link'].'/proposal/form/detail/'.$req->post('id'))]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function add_pembimbing(Request $req){
		$validasi = Validator::make($req->all(), [
			'id'=>'required|numeric',
			'dosen'=>'required|numeric',
			'dosen_ke'=>'required|numeric',
		], [
			'required'=>Mylib::validasi('required'),
			'numeric'=>Mylib::validasi('numeric'),
		], [
			'id'=>'ID', 'dosen'=>'Dosen Pembimbing', 'dosen_ke'=>'Pembimbing Ke',
		]);

		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}

		$cek = TaProposalPembimbingMdl::where('ta_proposal_id', $req->post('id'))->orderBy('id', 'desc')->first();
		if ($cek) {
			if ($cek->pegawai_id == $req->post('dosen')) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Dosen sudah terdaftar sebagai pembimbing.')]);
			}
			if ($cek->pembimbing_ke == $req->post('dosen_ke')) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Pembimbing ke-'.$req->post('dosen_ke').' sudah ada.')]);
			}
		}
		$user_ses = $req->session()->get('user_ses');
		$prep = new TaProposalPembimbingMdl();
		$prep->ta_proposal_id = $req->post('id');
		$prep->pembimbing_ke = $req->post('dosen_ke');
		$prep->pegawai_id = $req->post('dosen');
		$prep->created_at = date('Y-m-d H:i:s');
		$prep->created_by = $user_ses['id'];
		$prep->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pembimbing berhasil ditambahkan.')]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function delete_pembimbing(Request $req){
		$validasi = Validator::make($req->all(), [
			'id'=>'required|numeric',
		], [
			'required'=>Mylib::validasi('required'),
			'numeric'=>Mylib::validasi('numeric'),
		], [
			'id'=>'ID',
		]);

		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}

		$id = $req->post('id');

		$cek = TaProposalPembimbingMdl::where('id', $id)->first();
		if ($cek) {
			$cek_bimbingan = TaProposalBimbinganMdl::where('ta_proposal_pembimbing_id', $id)->first();
			if ($cek_bimbingan) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Pembimbing tidak bisa dihapus karena memiliki riwayat bimbingan.')]);
			}
			$cek->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pembimbing berhasil dihapus.')]);
		} else {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Pembimbing tidak ditemukan.')]);
		}
	}
	
}
