<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\BakSyaratMdl;
use \App\Models\BakTemplateMdl;
use \App\Models\UnitKerjaMdl;
use \App\Models\BakPengajuanSyaratMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Syarat extends Controller {

  // ------------------------------- separator -------------------------------
	public function list(Request $req){
    $modul = 'BakSyarat';

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
    
    if (!$f1) {
      $f1 = $user_ses['active_role']['unit_kerja_kode'];
    }

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = BakSyaratMdl::list($f1,$f2)->paginate($ppg);

		return view('bak.syarat_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'unit_kerja'=>UnitKerjaMdl::cmbAkademikByid($user_ses['active_role']['unit_kerja_kode'])->get(),
        'template'=>BakTemplateMdl::cmb()->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/syarat',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Syarat pengajuan',
        'links'=>[
          ['title'=>'Pengaturan','active'=>''],
          ['title'=>'Syarat pengajuan','active'=>'active'],
        ],
      ],
		]);
	}

  // ------------------------------- separator -------------------------------
	public function get(Request $req){
		$id = $req->post('id');
		$get = BakSyaratMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Syarat tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->unit_kerja_kode, $get->bak_template_kode, $get->nama, $get->is_aktif]]);
	}

  // ------------------------------- separator -------------------------------
  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|alpha_num',
			'in2' => 'required|alpha_dash',
			'in3' => 'required|string',
			'in4' => 'required|alpha',
		], [
			'required' => Mylib::validasi('required'),
			'required_if' => Mylib::validasi('required_if'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha_num' => Mylib::validasi('alpha_num'),
			'alpha_dash' => Mylib::validasi('alpha_dash'),
			'alpha' => Mylib::validasi('alpha'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Unit Kerja',
			'in2' => 'Template/Jenis Surat',
			'in3' => 'Syarat Pengajuan',
			'in4' => 'Aktif?',
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
				$prep = BakSyaratMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Syarat tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$prep = new BakSyaratMdl();
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
			}
			$prep->unit_kerja_kode = $in1;
			$prep->bak_template_kode = $in2;
			$prep->nama = $in3;
			$prep->is_aktif = $in4;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }

  // ------------------------------- separator -------------------------------
  public function delete(Request $req){
    $id = $req->post('id');
    $cek = BakPengajuanSyaratMdl::where('bak_syarat_id',$id)->first();
    if ($cek) {
      return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Tidak bisa menghapus data, Syarat sudah digunakan.')]);
    }
    try {
      $del = BakSyaratMdl::where('id',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }

}
