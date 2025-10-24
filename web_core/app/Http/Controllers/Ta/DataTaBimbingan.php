<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use \App\Models\TaJenisMdl;
use \App\Models\TaDataMdl;
use \App\Models\TaDataPembimbingMdl;
use \App\Models\TaDataBimbinganMdl;
use \App\Models\MahasiswaMdl;
use \App\Models\PegawaiMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class DataTaBimbingan extends Controller {
  
	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function list(Request $req){
    $modul = 'DataTaBimbingan';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}

		$get = TaDataMdl::getByid($id)->first();
		if (!$get) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}
		$ta_status = $get->ta_status_pengajuan_kode;

		$f1 = $req->post('f1');
		$filter = $req->post('filter');

		if ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
		}

		$arr_page = ['f1'=>$f1];
		$req->session()->put($modul, $arr_page);

		$dosen_pembimbing = TaDataPembimbingMdl::cmbJoinAll($id)->get();
		$tbl = TaDataBimbinganMdl::listBimbingan($id, $f1)->get();

		return view('ta.data.bimbingan_list', [
			'get'=>$get,
      'tbl'=>$tbl,
      'var'=>$arr_page,
      'cmb'=>[
        'dosen_pembimbing'=>$dosen_pembimbing,
      ],
			'segment_page'=>'bimbingan',
			'state_page'=>'update',
			'ta_status'=>$ta_status,
			'id_page'=>$enc_id,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$user_ses['active_app']['link'].'/data-ta/bimbingan/'.$enc_id,
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Bimbingan Tugas Akhir',
        'links'=>[
          ['title'=>'Tugas Akhir','active'=>''],
          ['title'=>'Daftar Bimbingan','active'=>'active'],
        ],
      ],
		]);
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function form(Request $req){
    $modul = 'DataTaBimbingan';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$act = $req->segment(6);
		$sid = $req->sid;
		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}
		
		$get = TaDataMdl::getByid($id)->first();
		if (!$get) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}
		$ta_status = $get->ta_status_pengajuan_kode;
		$dosen_pembimbing = TaDataPembimbingMdl::cmbJoinAll($id)->get();
		$form = ['in0'=>'', 'in1'=>'', 'in2'=>'', 'in3'=>'', 'in4'=>'', 'in5'=>'','in6'=>'', 'act'=>$act];

		if ($act == 'edit' && $sid != '') {
			$get_bimbingan = TaDataBimbinganMdl::where('id',$sid)->where('status_bimbingan','aktif')->first();
			if (!$get_bimbingan) {
				return redirect($user_ses['active_app']['link'].'/data-ta');
			}
			$form = [
				'in0'=>$get_bimbingan->id,
				'in1'=>$get_bimbingan->ta_data_pembimbing_id,
				'in2'=>Mylib::switch_tgl($get_bimbingan->tgl_bimbingan, 'short'),
				'in3'=>$get_bimbingan->topik,
				'in4'=>$get_bimbingan->bahasan,
				'in5'=>($get_bimbingan->lampiran) ? url($get_bimbingan->lampiran) : '',
				'in6'=>$get_bimbingan->bimbingan_ke,
				'act'=>$act
			];
		}		

		return view('ta.data.bimbingan_form', [
			'get'=>$get,
			'form'=>$form,
			'cmb'=>[
				'dosen_pembimbing'=>$dosen_pembimbing,
			],
			'segment_page'=>'bimbingan',
			'state_page'=>'update',
			'ta_status'=>$ta_status,
			'id_page'=>$id,
			'user_ses'=>$user_ses,
				'app_path'=>$user_ses['active_app']['link'],
				'ctr_path'=>$user_ses['active_app']['link'].'/data-ta/bimbingan/'.$enc_id,
				'mylib'=>Mylib::class,
			'page_title'=>[
				'icon'=>'fas fa-list',
				'bread'=>'Form Bimbingan Tugas Akhir',
				'links'=>[
				['title'=>'Tugas Akhir','active'=>''],
				['title'=>'Form Bimbingan','active'=>'active'],
				],
			],
		]);
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function save(Request $req){
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,edit|numeric',
			'in1' => 'required|numeric',
			'in2' => 'required|date_format:Y-m-d',
			'in3' => 'required|string',
			'in4' => 'required|string',
		], [
			'required' => Mylib::validasi('required'),
			'required_if' => Mylib::validasi('required_if'),
			'date_format' => Mylib::validasi('date_format'),
			'string' => Mylib::validasi('string'),
			'alpha' => Mylib::validasi('alpha'),
			'numeric' => Mylib::validasi('numeric'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Pembimbing',
			'in2' => 'Tanggal Bimbingan',
			'in3' => 'Topik',
			'in4' => 'Bahasan',
		]);
		if ($validasi->fails()) {
			$msg = 'Perhatian!';
			foreach ($validasi->errors()->all() as $err) { $msg .= '<br>- '.$err; }
			return response()->json(['status'=>'info', 'statusText'=>$msg]);
		}

		$proposal_id = "";
		$enc_id = $req->id;
		try {
			$proposal_id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','ID Proposal tidak valid.')]);
		}
		$act = $req->post('act');
    $user_ses = $req->session()->get('user_ses');

		$uploaded_file = '';
		$path_file = 'uploads/data-ta/'.date('Y').'/'.date('m');
		if ($req->hasFile('in5')) {
			$file = $req->file('in5');
			$maxsize = 5 * 1024 * 1024;
			if ($file->getSize() > $maxsize) {
				return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Ukuran Lampiran maximal 5MB.')]);
			}
			if ($file->getMimeType() != 'application/pdf') {
				return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Tipe file Lampiran harus PDF.')]);
			}
			$nama_file = $proposal_id.'_'.time().'.pdf';
			$file->move(public_path($path_file), $nama_file);
			$uploaded_file = $path_file.'/'.$nama_file;
		}		
		$bimbingan_ke = 1;
		$pembimbing_id = $req->post('in1');
		if ($act == 'add') {
			$bimbingan_terakhir = TaDataBimbinganMdl::lastBimbingan($proposal_id, $pembimbing_id)->first();
			if ($bimbingan_terakhir) {
				if ($bimbingan_terakhir->status_bimbingan == 'aktif') {
					return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Bimbingan sebelumnya belum selesai.')]);
				}
				$bimbingan_ke = $bimbingan_terakhir->bimbingan_ke + 1;
			} 			
			$prep = new TaDataBimbinganMdl();
			$prep->created_at = date('Y-m-d H:i:s');
			$prep->created_by = $user_ses['id'];
			$prep->ta_data_id = $proposal_id;
			$prep->ta_data_pembimbing_id = $pembimbing_id;
			$prep->bimbingan_ke = $bimbingan_ke;
			$prep->status_bimbingan = 'aktif';
		} else {
			$prep = TaDataBimbinganMdl::find($req->post('in0'));
			if (!$prep) {
				return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data bimbingan tidak ditemukan.')]);
			}
			$prep->updated_at = date('Y-m-d H:i:s');
			$prep->updated_by = $user_ses['id'];
		}
		$prep->tgl_bimbingan = $req->post('in2');
		$prep->topik = $req->post('in3');
		$prep->bahasan = $req->post('in4');
		if ($uploaded_file != '') {
			if ($prep->lampiran != "") { if (file_exists($prep->lampiran)) { unlink($prep->lampiran); } }
			$prep->lampiran = $uploaded_file;
		}
		$prep->save();
		return response()->json(['status'=>'success','statusText'=>Mylib::pesan('fail','custom','Data berhasil disimpan.')]);
	}
	
	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function detail(Request $req){
    $modul = 'DataTaBimbingan';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$sid = $req->sid;
		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}
		
		$get = TaDataMdl::getByid($id)->first();
		if (!$get) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}	
		$proposal_status = $get->ta_status_pengajuan_kode;

		$get_bimbingan = TaDataBimbinganMdl::getBimbinganByid($sid)->first();
		if (!$get_bimbingan) {
			return redirect($user_ses['active_app']['link'].'/data-ta');
		}

		return view('ta.proposal_bimbingan_detail', [
			'get'=>$get,
			'bimbingan'=>$get_bimbingan,
			'segment_page'=>'bimbingan',
			'state_page'=>'update',
			'proposal_status'=>$proposal_status,
			'id_page'=>$id,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$user_ses['active_app']['link'].'/data-ta/bimbingan/'.$enc_id,
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Form Bimbingan Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Detail Bimbingan Proposal','active'=>'active'],
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

    $cek_exist = TaDataMdl::where('ta_status_pengajuan_kode', '!=', 'ditolak')->where('mahasiswa_nim',$in1)
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

		$skripsi_found = 0;
		if ($act == 'save') {			
			$http = Http::withHeaders([
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
				'X-App-Key' => env('X_APP_KEY'),
				'X-Secret-Key' => env('X_SECRET_KEY'),
			])->timeout(3)->get(env('SEVIMA_URL').'/mahasiswa/'.$in1.'/krs');
			
			if (!$http->ok()) {
        return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Tidak bisa memverifikasi krs mahasiswa ke SIAKAD.')]);
      } else {
				$body = $http->json();
        $datas = $body['data'];
				foreach ($datas as $data) {
					$attr = $data['attributes'];
					if (in_array($attr['mata_kuliah'], ['Skripsi','skripsi'])) {
						if ($attr['is_krs_diajukan'] == 1 && $attr['is_krs_disetujui'] == 1) { ++$skripsi_found; }
					}
				}
			}
		}
		
		try {
			if ($act == 'update') {
				$prep = TaDataMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Pengajuan tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				if ($skripsi_found == 0) {
					return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Mahasiswa belum memiliki KRS Skripsi / Pengajuan KRS Skripsi belum disetujui, silahkan cek KRS di SIAKAD.')]);
				}
				$prep = new TaDataMdl();
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
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save'), 'directto'=>url($user_ses['active_app']['link'].'/data-ta/form/detail/'.$prep->id)]);
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
		$get = TaDataMdl::find($req->post('id'));
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Data pengajuan tidak ditemukan.')]);
		}
    $user_ses = $req->session()->get('user_ses');

		$get->ta_status_pengajuan_kode = 'ditolak';
		$get->ta_status_pengajuan_ket = $req->post('ket');
		$get->tolak_at = date('Y-m-d H:i:s');
		$get->tolak_by = $user_ses['id'];
		$get->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pengajuan berhasil diupdate.'), 'directto'=>url($user_ses['active_app']['link'].'/data-ta/form/detail/'.$req->post('id'))]);
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
		$get = TaDataMdl::find($req->post('id'));
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Data pengajuan tidak ditemukan.')]);
		}
    $user_ses = $req->session()->get('user_ses');

		$get->ta_status_pengajuan_kode = 'disetujui';
		$get->acc_at = date('Y-m-d H:i:s');
		$get->acc_by = $user_ses['id'];
		$get->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pengajuan berhasil diupdate.'), 'directto'=>url($user_ses['active_app']['link'].'/data-ta/form/detail/'.$req->post('id'))]);
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

		$cek = TaDataPembimbingMdl::where('ta_data_id', $req->post('id'))->orderBy('id', 'desc')->first();
		if ($cek) {
			if ($cek->pegawai_id == $req->post('dosen')) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Dosen sudah terdaftar sebagai pembimbing.')]);
			}
			if ($cek->pembimbing_ke == $req->post('dosen_ke')) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Pembimbing ke-'.$req->post('dosen_ke').' sudah ada.')]);
			}
		}
		$user_ses = $req->session()->get('user_ses');
		$prep = new TaDataPembimbingMdl();
		$prep->ta_data_id = $req->post('id');
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

		$cek = TaDataPembimbingMdl::where('id', $id)->first();
		if ($cek) {
			$cek_bimbingan = TaDataBimbinganMdl::where('ta_data_pembimbing_id', $id)->first();
			if ($cek_bimbingan) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Pembimbing tidak bisa dihapus karena memiliki riwayat bimbingan.')]);
			}
			$cek->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Pembimbing berhasil dihapus.')]);
		} else {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Pembimbing tidak ditemukan.')]);
		}
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function list_bimbingan_dosen(Request $req){
    $modul = 'TaDataBimbinganByDosen';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$filter = $req->post('filter');
		$act = $req->get('act');

		if ($act == 'reset') {
			$f1 = ''; $f2 = 'aktif';
		} elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : 'aktif';
		}

		$arr_page = ['f1'=>$f1, 'f2'=>$f2];
		$req->session()->put($modul, $arr_page);

		$mahasiswa_bimbingan = TaDataPembimbingMdl::cmbMahasiswaBimbingan($user_ses['pegawai_id'], $f1)->get();
		$tbl = TaDataBimbinganMdl::listBimbinganByDosen($user_ses['pegawai_id'], $f1, $f2)->get();

		return view('ta.data.bimbingan_list_bydosen', [
      'tbl'=>$tbl,
      'var'=>$arr_page,
      'cmb'=>[
        'mahasiswa_bimbingan'=>$mahasiswa_bimbingan,
				'status_bimbingan'=>Mylib::status_bimbingan(),
      ],
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$user_ses['active_app']['link'].'/data-ta/bimbingan/dosen',
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'List Bimbingan Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Daftar Bimbingan Proposal','active'=>'active'],
        ],
      ],
		]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function detail_bimbingan_dosen(Request $req){
    $modul = 'TaDataBimbinganByDosen';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$ctr_link = $user_ses['active_app']['link'].'/data-ta';

		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($ctr_link);
		}

		$bimbingan = TaDataBimbinganMdl::getBimbinganByid($id)->first();
		if (!$bimbingan) {
			return redirect($ctr_link);
		}
		$get = TaDataMdl::getByid($bimbingan->ta_data_id)->first();
		if (!$get) {
			return redirect($ctr_link);
		}
		$bimbingan_ke = TaDataBimbinganMdl::listBimbinganKe($bimbingan->ta_data_id, $user_ses['pegawai_id'])->get();

		return view('ta.proposal_bimbingan_detail_bydosen', [
			'get'=>$get,
      'bimbingan'=>$bimbingan,
      'bimbingan_ke'=>$bimbingan_ke,
      'cmb'=>[
				'status_bimbingan'=>Mylib::status_bimbingan(),
				'status_disetujui'=>Mylib::cmb_status_disetujui(),
      ],
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$user_ses['active_app']['link'].'/data-ta/bimbingan/dosen',
			'enc_id'=>$enc_id,
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'List Bimbingan Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Daftar Bimbingan Proposal','active'=>'active'],
        ],
      ],
		]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function save_bimbingan_dosen(Request $req){
		$validasi = Validator::make($req->all(), [
			'id'=>'required|numeric',
			'in1'=>'required|string',
			'in2'=>'required|string',
		], [
			'required'=>Mylib::validasi('required'),
			'numeric'=>Mylib::validasi('numeric'),
			'string'=>Mylib::validasi('string'),
		], [
			'id'=>'ID','in1'=>'Disetujui/Tolak', 'in2'=>'Komentar',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$msg)]);
		}

		$prep = TaDataBimbinganMdl::find($req->post('id'));
		if (!$prep) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Data Bimbingan tidak ditemukan.')]);
		}

    $user_ses = $req->session()->get('user_ses');
		try {			
			$prep->status_disetujui = $req->post('in1');
			$prep->catatan_pembimbing = $req->post('in2');
			$prep->status_bimbingan = "selesai";
			$prep->updated_at = date('Y-m-d H:i:s');
			$prep->updated_by = $user_ses['id'];
			$prep->save();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success', 'save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}
	
}
