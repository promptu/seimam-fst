<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use \App\Models\BakPengajuanMdl;
use \App\Models\BakPengajuanSyaratMdl;
use \App\Models\BakTemplateMdl;
use \App\Models\BakSyaratMdl;

use \App\Library\Mylib;
use \App\Library\Dropdown;

class Layanan extends Controller {
  
  // ------------------------------- separator -------------------------------
	public function list(Request $req){
    $modul = 'BakLayanan';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);

		$f1 = $req->post('f1');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');

    if ($act == 'reset') {
      $f1 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);
		$arr_var = ['f1'=>$f1, 'ppg'=>$ppg, 'lastno'=>$lastno];

		$req->session()->put($modul, $arr_var);

    $tbl = bakPengajuanMdl::listByMhs($user_ses['mahasiswa_nim'], $f1)->paginate($ppg);

		return view('bak.layanan_mhs_list', [
      'tbl'=>$tbl,
      'var'=>$arr_var,
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'template'=>BakTemplateMdl::cmb()->get(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/layanan',
			'mylib'=>Mylib::class,
			'crypt'=>Crypt::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Layanan BAK',
        'links'=>[
          ['title'=>'Layanan BAK','active'=>'active'],
        ],
      ],
		]);
	}

  // ------------------------------- separator -------------------------------
	public function get(Request $req){
		$id = $req->post('id');
		$get = BakPengajuanMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Syarat tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->bak_template_kode, $get->keperluan]]);
	}

  // ------------------------------- separator -------------------------------
  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|alpha_dash',
			'in2' => 'required|string',
		], [
			'required' => Mylib::validasi('required'),
			'required_if' => Mylib::validasi('required_if'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
			'alpha_dash' => Mylib::validasi('alpha_dash'),
			'alpha' => Mylib::validasi('alpha'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Jenis Surat',
			'in2' => 'Keperluan',
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
		$dtm = date('Y-m-d H:i:s');
		
		try {
			if ($act == 'update') {
				$prep = BakPengajuanMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Pengajuan tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
				$prep->updated_by = $user_ses['id'];
			} else {
				$cek = BakPengajuanMdl::where('mahasiswa_nim', $user_ses['mahasiswa_nim'])->where('bak_template_kode', $in1)->whereIn('status', ['DRAFT','PENGAJUAN','PROSES'])->first();
				if ($cek) {
					return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Anda sedang melakukan pengajuan untuk jenis surat ini.')]);
				}
				$prep = new BakPengajuanMdl();
				$prep->mahasiswa_nim = $user_ses['mahasiswa_nim'];
				$prep->unit_kerja_kode = $user_ses['active_role']['unit_kerja_kode'];
				$prep->created_at = $dtm;
				$prep->created_by = $user_ses['id'];
				$prep->status = 'DRAFT';
			}
			$prep->bak_template_kode = $in1;
			$prep->keperluan = $in2;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save'), 'tourl'=>url($user_ses['active_app']['link'].'/layanan/detail').'/'.Crypt::encryptString($prep->id)]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }

  // ------------------------------- separator -------------------------------
  public function detail(Request $req){
    $modul = 'BakLayanan';
    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
		$enc_id = $req->id;
		$id = '';
		$main_url = $user_ses['active_app']['link'].'/layanan';
		$back_url = $main_url.((isset($ctr_ses['page'])) ? '?page='.$ctr_ses['page'] : '');

		try {
			$id = Crypt::decryptString($enc_id);
		} catch (DecryptException $th) {
			return redirect($main_url);
		}

		$pengajuan = BakPengajuanMdl::byId($id)->first();
		if (!$pengajuan) {
			return redirect($main_url);
		}

		$arr_syarat = [];
		$get_syarat = BakSyaratMdl::where('bak_template_kode', $pengajuan->bak_template_kode)->where('unit_kerja_kode', $user_ses['active_role']['unit_kerja_kode'])->where('is_aktif', 'Y')->get();
		foreach ($get_syarat as $syarat) {
			$arr_syarat[$syarat->id] = [
				'bak_syarat_id' => $syarat->id,
				'nama' => $syarat->nama,
				'bak_template_kode' => $syarat->bak_template_kode
			];
			$get_syarat_upload = BakPengajuanSyaratMdl::where('bak_syarat_id', $syarat->id)->where('bak_pengajuan_id', $pengajuan->id)->first();
			if ($get_syarat_upload) {
				$arr_syarat[$syarat->id]['bak_pengajuan_syarat_id'] = $get_syarat_upload->id;
				$arr_syarat[$syarat->id]['berkas'] = $get_syarat_upload->berkas;
				$arr_syarat[$syarat->id]['is_valid'] = $get_syarat_upload->is_valid;
				$arr_syarat[$syarat->id]['validated_at'] = $get_syarat_upload->validated_at;
				$arr_syarat[$syarat->id]['validated_by'] = $get_syarat_upload->validated_by;
			} else {
				$ins_syarat_upload = new BakPengajuanSyaratMdl();
				$ins_syarat_upload->bak_pengajuan_id = $pengajuan->id;
				$ins_syarat_upload->bak_syarat_id = $syarat->id;
				$ins_syarat_upload->save();

				$arr_syarat[$syarat->id]['bak_pengajuan_syarat_id'] = ($ins_syarat_upload->id) ? $ins_syarat_upload->id : "";
				$arr_syarat[$syarat->id]['berkas'] = "";
				$arr_syarat[$syarat->id]['is_valid'] = "";
				$arr_syarat[$syarat->id]['validated_at'] = "";
				$arr_syarat[$syarat->id]['validated_by'] = "";
			}
		}	

		return view('bak.layanan_mhs_detail', [
			'pengajuan'=>$pengajuan,
			'arr_syarat'=>$arr_syarat,
			'main_url'=>$main_url,
			'back_url'=>$back_url,
			'mylib'=>Mylib::class,
      'user_ses'=>$user_ses,
      'page_title'=>[
        'icon'=>'fas fa-server',
        'bread'=>'Detail Pengajuan',
        'links'=>[
          ['title'=>'Layanan BAK','active'=>''],
          ['title'=>'Detail Pengajuan','active'=>'active'],
        ],
			],
		]);
  }

  // ------------------------------- separator -------------------------------
  public function delete(Request $req){
    $id = $req->post('id');
    try {
      $del = BakSyaratMdl::where('id',$id)->where('status','PENGAJUAN')->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }

  // ------------------------------- separator -------------------------------
	public function upload_berkas(Request $req){
    $modul = 'BakLayanan';
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
			'mid' => 'ID Pengajuan',
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
		$pathfile = 'uploads/bak/'.date('Y').'/'.date('m');
		$req->file('file')->move(public_path($pathfile), $namafile);

    try {
			$prep = BakPengajuanSyaratMdl::where('id', $id)->where('bak_pengajuan_id', $mid)->first();
			if (!$prep) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Data tidak ditemukan.')]);
			}
			$prep->berkas = $pathfile.'/'.$namafile;
			$prep->save();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom', 'Berkas berhasil diupload.')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}

  // ------------------------------- separator -------------------------------
	public function delete_berkas(Request $req){
		$id = $req->post('id');
    try {
			$get = BakPengajuanSyaratMdl::where('id', $id)->first();
			if (!$get) {
				return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', 'Data tidak ditemukan.')]);
			}
			if (file_exists($get->berkas)) {
				unlink($get->berkas);
			}
			$get->berkas = "";
			$get->save();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom', 'Berkas berhasil dihapus.')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
	}

  // ------------------------------- separator -------------------------------
	public function pengajuan_validasi(Request $req){
		$mid = $req->post('mid');
		$get = BakPengajuanMdl::find($mid);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom', 'Data Pengajuan tidak ditemukan.')]);
		}
		$get->status = 'PENGAJUAN';
		$get->save();
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom', 'Pengajuan validasi berkas berhasil.')]);
	}

}
