<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use \App\Models\TaProposalSyaratUjianMdl;
use \App\Models\TaProposalSyaratUjianUploadMdl;
use \App\Models\TaProposalMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\TaJenisMdl;
use \App\Models\PeriodeMdl;
use \App\Models\TaStatusPengajuanMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class ProposalSyaratUjian extends Controller {
  

	public function list(Request $req){
		$modul = 'TaProposalSyaratUjian';

		$user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');

		if ($act == 'reset') {
		$f1 = 00; $f2 = ''; $ppg = 10;
		} elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '00';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

		$tbl = TaProposalSyaratUjianMdl::list($f1,$f2)->paginate($ppg);

		return view('ta.proposal_syarat_ujian_list', [
			'tbl'=>$tbl,
			'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
			'cmb'=>[
				'ppg'=>Mylib::ppg(),
				'unit'=>UnitKerjaMdl::cmb()->get(),
				'ta_jenis'=>TaJenisMdl::cmbJenis()->get(),
				'is_aktif'=>Mylib::is_aktif(),
			],
			'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/syarat-ujian-proposal',
			'mylib'=>Mylib::class,
			'page_title'=>[
				'icon'=>'fas fa-list',
				'bread'=>'Syarat Ujian Proposal',
				'links'=>[
				['title'=>'Proposal Tugas Akhir','active'=>''],
				['title'=>'Syarat Ujian Proposal','active'=>'active'],
				],
			],
		]);
	}



	public function get(Request $req){
		$id = $req->post('id');
		$get = TaProposalSyaratUjianMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->unit_kerja_kode, $get->ta_jenis_kode, $get->nama, $get->is_aktif]]);

	}



  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|alpha_num',
			'in2' => 'required|alpha_num',
			'in3' => 'required|string',
			'in4' => 'required|alpha',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha' => Mylib::validasi('alpha'),
			'alpha_num' => Mylib::validasi('alpha_num'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Unit Kerja',
			'in2' => 'Jenis Ta.',
			'in3' => 'Nama Syarat.',
			'in4' => 'Status Aktif',
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
				$prep = TaProposalSyaratUjianMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Jabatan tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$prep = new TaProposalSyaratUjianMdl();
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
			}
			$prep->unit_kerja_kode = $in1;
			$prep->ta_jenis_kode = $in2;
			$prep->nama = $in3;
			$prep->is_aktif = $in4;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }



  public function delete(Request $req){
    $id = $req->post('id');
    $cek = TaProposalSyaratUjianUploadMdl::where('ta_proposal_syarat_ujian_id',$id)->first();
    if ($cek) {
      return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Tidak bisa menghapus data, Data sudah digunakan.')]);
    }
    try {
      $del = TaProposalSyaratUjianMdl::where('id',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }



	public function list_by_proposal(Request $req){
    $modul = 'TaProposalSyaratUjian';
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
		$syarat_ujian = TaProposalSyaratUjianMdl::listByProposal2($get->prodi_kode, $get->ta_jenis_kode, $id)->get();

		return view('ta.proposal_bimbingan_syarat_ujian', [
			'get'=>$get,
      'syarat_ujian'=>$syarat_ujian,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$user_ses['active_app']['link'].'/proposal/syarat-ujian',
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
			'segment_page'=>'syarat_ujian',
			'state_page'=>'update',
			'proposal_status'=>$get->ta_status_pengajuan_kode,
			'id_proposal'=>$id,
			'id_page'=>$enc_id,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Syarat Ujian Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Syarat Ujian Proposal','active'=>'active'],
        ],
      ],
		]);
	}


	public function upload_berkas(Request $req){
    $modul = 'TaProposalSyaratUjian';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$validasi = Validator::make($req->all(), [
			'id' => 'required|numeric',
			'mid' => 'required|numeric',
			'file' => 'file|max:1024|mimes:pdf',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
			'file' => Mylib::validasi('file'),
			'file.max' => 'Ukuran file maximal 1MB',
			'file.mimes' => 'Tipe file harus PDF',
		], [
			'id' => 'ID Berkas',
			'mid' => 'ID Proposal',
			'file' => 'Berkas',
		]);
		if ($validasi->fails()) {
			$msg = 'Perhatian!';
			foreach ($validasi->errors()->all() as $err) { $msg .= '<br>- '.$err; }
			return response()->json(['status'=>'info', 'statusText'=>$msg]);
		}

		$id = $req->post('id');
		$mid = $req->post('mid');

		$namafile = $mid.'_'.$id.'_'.time().'.'.$req->file('file')->extension();
		$pathfile = 'uploads/syarat-ujian/'.date('Y').'/'.date('m');
		$req->file('file')->move(public_path($pathfile), $namafile);

    try {
			$prep = TaProposalSyaratUjianUploadMdl::where('ta_proposal_id', $mid)->where('ta_proposal_syarat_ujian_id', $id)->first();
			if (!$prep) {
				$prep = new TaProposalSyaratUjianUploadMdl();
				$prep->ta_proposal_id = $mid;
				$prep->ta_proposal_syarat_ujian_id = $id;
			}
			$prep->berkas = $pathfile.'/'.$namafile;
			$prep->uploaded_at = date('Y-m-d H:i:s');
			$prep->uploaded_by = $user_ses['id'];
			$prep->save();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom', 'Berkas berhasil diupload.')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}


	public function delete_berkas(Request $req){
		$id = $req->post('id');
		$mid = $req->post('mid');
    try {
			$get = TaProposalSyaratUjianUploadMdl::where('ta_proposal_id', $mid)->where('ta_proposal_syarat_ujian_id', $id)->first();
			if (!$get) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data tidak ditemukan.')]);
			}
			if ($get->is_valid == 'Y') {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Berkas sudah divalidasi, tidak bisa dihapus.')]);
			}
			if (file_exists($get->berkas)) { unlink($get->berkas); }
			$get->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom', 'Berkas berhasil dihapus.')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}


	public function pengajuan_validasi(Request $req){
		$mid = $req->post('mid');
		$get = TaProposalMdl::find($mid);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Data Proposal tidak ditemukan.')]);
		}
		$get->status_berkas = 'PENGAJUAN';
		$get->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom', 'Pengajuan validasi berkas berhasil.')]);
	}
	
	
	public function validasi_list(Request $req){
    $modul = 'TaProposalSyaratUjianValidasi';

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
    if (!$f4) {
      $f4 = "PENGAJUAN";
    }


		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

		$arr_page = ['f1'=>$f1, 'f2'=>$f2, 'f3'=>$f3, 'f4'=>$f4, 'f5'=>$f5, 'ppg'=>$ppg, 'lastno'=>$lastno, 'page'=>$page];
		$req->session()->put($modul, $arr_page);

    $tbl = TaProposalMdl::listStatusBerkas($f1,$f2,$f3,$f4,$f5)->paginate($ppg);

		return view('ta.proposal_syarat_ujian_validasi_list', [
      'tbl'=>$tbl,
      'var'=>$arr_page,
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'ta_jenis'=>TaJenisMdl::cmbJenis()->get(),
        'prodi'=>UnitKerjaMdl::cmbAkademikByid($user_ses['active_role']['unit_kerja_kode'])->get(),
        'periode'=>PeriodeMdl::cmb()->get(),
        'status_berkas'=>Mylib::status_berkas(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/syarat-ujian-proposal/validasi',
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


	public function validasi_detail(Request $req){
    $modul = 'TaProposalSyaratUjianValidasi';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$ctr_link = $user_ses['active_app']['link'].'/syarat-ujian-proposal/validasi';

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
		$syarat_ujian = TaProposalSyaratUjianMdl::listByProposal2($get->prodi_kode, $get->ta_jenis_kode, $id)->get();

		return view('ta.proposal_syarat_ujian_validasi_detail', [
			'get'=>$get,
      'syarat_ujian'=>$syarat_ujian,
      'user_ses'=>$user_ses,
			'app_path'=>$user_ses['active_app']['link'],
			'ctr_path'=>$ctr_link,
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
			'segment_page'=>'syarat_ujian',
			'state_page'=>'update',
			'proposal_status'=>$get->ta_status_pengajuan_kode,
			'id_proposal'=>$id,
			'id_page'=>$enc_id,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Syarat Ujian Proposal',
        'links'=>[
          ['title'=>'Proposal Tugas Akhir','active'=>''],
          ['title'=>'Syarat Ujian Proposal','active'=>'active'],
        ],
      ],
		]);
	}


  public function validasi_save(Request $req){    
		$validasi = Validator::make($req->all(), [
			'id' => 'required|numeric',
			'in' => 'required',
		], [
			'required' => Mylib::validasi('required'),
			'numeric' => Mylib::validasi('numeric'),
		], [
			'id' => 'ID',
			'in' => 'Input',
		]);
		if ($validasi->fails()) {
			$msg = '';
			foreach ($validasi->errors()->all() as $err) { $msg .= '- '.$err.'<br>'; }
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg)]);
		}
		$id = $req->post('id');
		$in = $req->post('in');
		$user_ses = $req->session()->get('user_ses');
		$dtm = date('Y-m-d H:i:s');
		$user_id = $user_ses['id'];

		DB::beginTransaction();
		try {
			$err_count = 0; $count_invalid = 0;
			foreach ($in as $key => $value) {
				if (!in_array($value, ["Y","T"])) { $err_count++; } else {
					if ($value == 'T') { $count_invalid++; }
					TaProposalSyaratUjianUploadMdl::where('ta_proposal_id',$id)->where('ta_proposal_syarat_ujian_id', $key)->update(['is_valid'=>$value, 'validated_at'=>$dtm, 'validated_by'=>$user_id]);
				}
			}
			if ($err_count > 0) {
				DB::rollBack();
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom',$err_count.' berkas belum divalidasi.')]);
			} else {
				$text_status_valid = ($count_invalid > 0) ? 'INVALID' : 'VALID';
				TaProposalMdl::where('id',$id)->update(['status_berkas'=>$text_status_valid, 'updated_at'=>$dtm, 'updated_by'=>$user_id]);
			}
			DB::commit();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Validasi sukses.')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			DB::rollBack();
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
		
  }




}
