<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use \App\Models\InventoriKategoriMdl;



use \App\Library\Mylib;
use \App\Library\Dropdown;

class BarangKategori extends Controller {
  

  public function list(Request $req){
    $modul = 'InventoriBarangKategori';

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

		$req->session()->put($modul, ['f1'=>$f1,  'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = InventoriKategoriMdl::list($f1)->paginate($ppg);

		return view('inventori.barang_kategori', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'kategori'=>InventoriKategoriMdl::cmb()->get(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/kategori',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Kategori Barang',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Kategori Barang','active'=>'active'],
        ],
      ],
		]);
  }



	public function get(Request $req){
		$id = $req->post('id');
		$get = InventoriKategoriMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Barang tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->nama]]);

	}



  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|string',
		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Nama Barang',
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
		$dtm = date('Y-m-d H:i:s');
		
		try {
			if ($act == 'update') {
				$prep = InventoriKategoriMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Barang tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
			} else {
				$prep = new InventoriKategoriMdl();
				$prep->created_at = $dtm;
			}
			$prep->nama = $in1;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }



  public function delete(Request $req){
    $id = $req->post('id');
    try {
      $del = InventoriKategoriMdl::where('id',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }




}
