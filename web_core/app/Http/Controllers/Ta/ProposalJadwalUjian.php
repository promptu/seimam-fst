<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use \App\Models\TaProposalSyaratUjianMdl;
use \App\Models\TaProposalMdl;
use \App\Models\TaProposalPengujiMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\TaJenisMdl;
use \App\Models\PeriodeMdl;
use \App\Models\TaRuangUjianMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class ProposalJadwalUjian extends Controller {
  
	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function list(Request $req){
    $modul = 'TaProposalJadwalUjian';

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
      $f1 = ''; $f2 = ''; $ppg = 10;
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

    $tbl = TaProposalMdl::listStatusBerkasValid($f1,$f2,$f3,$f4,$f5)->paginate($ppg);

		return view('ta.proposal_set_jadwal_ujian_list', [
      'tbl'=>$tbl,
      'var'=>$arr_page,
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'ta_jenis'=>TaJenisMdl::cmbJenis()->get(),
        'prodi'=>UnitKerjaMdl::cmbAkademikByid($user_ses['active_role']['unit_kerja_kode'])->get(),
        'periode'=>PeriodeMdl::cmb()->get(),
        'status_lulus'=>Mylib::status_lulus(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/proposal/set-jadwal-ujian',
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Daftar Proposal',
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
	public function detail(Request $req){
    $modul = 'TaProposalJadwalUjian';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$ctr_link = $user_ses['active_app']['link'].'/proposal/set-jadwal-ujian';

		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($ctr_link);
		}

		$get = TaProposalMdl::getByid($id)->first();
		if (!$get) {
			return redirect($ctr_link);
		}

		return view('ta.proposal_jadwal_ujian_detail', [
			'get'=>$get,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$ctr_link,
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
			'cmb'=>[
				'ruang'=> TaRuangUjianMdl::cmb()->get(),
			],
			'proposal_status'=>$get->ta_status_pengajuan_kode,
			'id_proposal'=>$id,
			'id_page'=>$enc_id,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Jadwal Ujian Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Jadwal Ujian Proposal','active'=>'active'],
        ],
      ],
		]);
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function update(Request $req){    
		$validasi = Validator::make($req->all(), [
			'in0' => 'required|numeric',
			'in1' => 'required|alpha_num',
			'in2' => 'required|date_format:Y-m-d',
			'in3' => 'required|date_format:H:i',
			'in4' => 'required|date_format:H:i',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha_num' => Mylib::validasi('alpha_num'),
			'date_format' => Mylib::validasi('date_format'),
		], [
			'in0' => 'ID Proposal',
			'in1' => 'Ruang Ujian',
			'in2' => 'Tanggal Ujian',
			'in3' => 'Jam Mulai',
			'in4' => 'Jam Selesai',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}
		$in0 = $req->post('in0');
		$in1 = $req->post('in1');
		$in2 = $req->post('in2');
		$in3 = $req->post('in3');
		$in4 = $req->post('in4');
		$user_ses = $req->session()->get('user_ses');
		$dtm = date('Y-m-d H:i:s');
		$user_id = $user_ses['id'];

		try {
			$prep = TaProposalMdl::find($in0);
			if (!$prep) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data proposal tidak ditemukan.')]);
			}
			$prep->ta_ruang_ujian_kode = $in1;
			$prep->tgl_ujian_mulai = $in2." ".$in3.":00";
			$prep->tgl_ujian_selesai = $in2." ".$in4.":59";
			$prep->save();

			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','update')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			DB::rollBack();
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}		
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
  public function add_penguji(Request $req){    
		$validasi = Validator::make($req->all(), [
			'in0' => 'required|numeric',
			'in1' => 'required|numeric',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
		], [
			'in0' => 'ID Proposal',
			'in1' => 'Dosen Penguji',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}
		$in0 = $req->post('in0');
		$in1 = $req->post('in1');
		$user_ses = $req->session()->get('user_ses');
		$dtm = date('Y-m-d H:i:s');
		$user_id = $user_ses['id'];

		try {
			$prep = new TaProposalPengujiMdl();
			$prep->ta_proposal_id = $in0;
			$prep->pegawai_id = $in1;
			$prep->save();

			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','update')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			DB::rollBack();
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}		
  }

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function delete_penguji(Request $req){
		$id = $req->post('id');
		$delete = TaProposalPengujiMdl::where('id',$id)->delete();
		if ($delete) {
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
		} else {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Gagal menghapus data penguji.')]);
		}
	}

	// -------------------------------------------------------------------------------------------
	// function separator
	// -------------------------------------------------------------------------------------------
	public function mahasiswa_detail(Request $req){
    $modul = 'TaProposalJadwalUjian';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$ctr_link = $user_ses['active_app']['link'].'/proposal';

		$enc_id = $req->id;
		$id = '';
		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($ctr_link);
		}

		$get = TaProposalMdl::getByid($id)->first();
		if (!$get) {
			return redirect($ctr_link);
		}

		return view('ta.proposal_jadwal_ujian_detail_mhs', [
			'get'=>$get,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$ctr_link,
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
			'cmb'=>[
				'ruang'=> TaRuangUjianMdl::cmb()->get(),
			],
			'segment_page'=>'jadwal_ujian',
			'proposal_status'=>$get->ta_status_pengajuan_kode,
			'id_proposal'=>$id,
			'id_page'=>$enc_id,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Jadwal Ujian Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Jadwal Ujian Proposal','active'=>'active'],
        ],
      ],
		]);
	}

}
