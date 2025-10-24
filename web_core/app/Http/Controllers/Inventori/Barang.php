<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\RoleMdl;
use \App\Models\PenggunaRoleMdl;
use \App\Models\InventoriBarangMdl;
use \App\Models\InventoriKategoriMdl;



use \App\Library\Mylib;
use \App\Library\Dropdown;

class Barang extends Controller {
  

  public function list(Request $req){
    $modul = 'InventoriBarang';

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

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = InventoriBarangMdl::list($f1,$f2)->paginate($ppg);

		return view('inventori.barang_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'kategori'=>InventoriBarangMdl::cmb()->get(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/barang',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Stok Barang',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Stok Barang','active'=>'active'],
        ],
      ],
		]);
  }



	public function get(Request $req){
		$id = $req->post('id');
		$get = InventoriBarangMdl::find($id);
		if (!$get) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail','custom','Data Barang tidak ditemukan.')]);
		}
		return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','custom','Data dimuat.'), 'datalist'=>[$get->id, $get->nama, $get->kode_barang, $get->kategori_id,$get->jumlah_stock,$get->satuan,]]);

	}



  public function simpan(Request $req){    
		$validasi = Validator::make($req->all(), [
			'act' => 'required|alpha',
			'in0' => 'nullable|required_if:act,==,update|numeric',
			'in1' => 'required|string',
			'in2' => 'required|string',
			'in3' => 'required|numeric',
			'in4' => 'required|numeric',
			'in5' => 'required|string',

		], [
			'required' => Mylib::validasi('required'),
			'string' => Mylib::validasi('string'),
			'numeric' => Mylib::validasi('numeric'),
		], [
			'act' => 'ACT',
			'in0' => 'ID',
			'in1' => 'Nama Barang',
			'in2' => 'Kode Barang',
			'in3' => 'Kategori',
			'in4' => 'Jumlah Stock',
			'in5' => 'Satuan',
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
		$in5 = $req->post('in5');

		$dtm = date('Y-m-d H:i:s');
		
		try {
			if ($act == 'update') {
				$prep = InventoriBarangMdl::find($in0);
				if (!$prep) { return response()->json(['status'=>'info','statusText'=>Mylib::pesan('fail','custom','Data Barang tidak ditemukan.')]); }
				$prep->updated_at = $dtm;
			} else {
				$prep = new InventoriBarangMdl();
				$prep->created_at = $dtm;
			}
			$prep->nama = $in1;
			$prep->kode_barang = $in2;
			$prep->kategori_id = $in3;
			$prep->jumlah_stock = $in4;
			$prep->satuan = $in5;
			$prep->save();
			
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','save')]);
		} catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }



  public function delete(Request $req){
    $id = $req->post('id');
    try {
      $del = InventoriBarangMdl::where('id',$id)->delete();
			return response()->json(['status'=>'success', 'statusText'=>Mylib::pesan('success','delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
			return response()->json(['status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
		}
  }





//   public function verif(Request $req) {
// 	$id = $req->get('id', $req->id);  // Terima ID dari add atau query string
// 	$user_ses = $req->session()->get('user_ses');
// 	$selectedItems = $req->post('selected_items');
// 	$action = $req->post('action');  // Ambil action dari tombol
// 	$ctr_path = $req->input('ctr_path', $user_ses['active_app']['link'].'/verifikasi/form/'.$id);
  
// 	if (empty($selectedItems)) {
// 		return response()->json([
// 			'status' => 'info', 
// 			'statusText' => 'Tidak ada item yang dipilih untuk diverifikasi.'
// 		]);
// 	}
  
// 	// return $selectedItems;
	
// 	try {
// 		// Inisialisasi array untuk menyimpan ID dari pengajuan yang perlu diupdate
// 		$pengajuanIds = [];
  
// 		// Loop melalui setiap item yang dipilih
// 		foreach ($selectedItems as $detailId) {
// 			// Dapatkan detail barang keluar berdasarkan ID
// 			$detail = InventoriBarangKeluarDetailMdl::find($detailId);
  
// 			if ($detail) {
// 			  // Dapatkan stok barang dari tabel inventori_barang
// 			  $barang = InventoriBarangMdl::where('id', $detail->barang_id)->first();
  
// 			  if ($action == 'approve') {
// 				  // Validasi: Cek apakah jumlah yang diajukan melebihi stok
// 				  if ($detail->jumlah > $barang->jumlah_stock) {
// 					  // Jika stok tidak mencukupi, kembalikan pesan error
// 					  return response()->json([
// 						  'status' => 'error',
// 						  'statusText' => 'Jumlah yang diajukan melebihi jumlah stok yang tersedia untuk barang: ' . $barang->nama_barang
// 					  ]);
// 				  }
  
// 				  // Jika stok mencukupi, kurangi stok barang
// 				  $barang->decrement('jumlah_stock', $detail->jumlah);
// 					// Update status menjadi 'disetujui'
// 				  $detail->status = 'disetujui';  // Sesuaikan dengan nilai status yang sesuai
// 				  $detail->tanggal_verifikasi = now();
// 				} else if ($action == 'reject') {
// 					// Update status menjadi 'ditolak'
// 					$detail->status = 'ditolak';  // Sesuaikan dengan nilai status yang sesuai
// 					$detail->tanggal_verifikasi = now();
// 				}
  
// 				$detail->save();
  
// 				// Simpan ID pengajuan untuk update status di tabel InventoriBarangKeluar
// 				if (!in_array($detail->barang_keluar_id, $pengajuanIds)) {
// 					$pengajuanIds[] = $detail->barang_keluar_id;
// 				}
// 			}
// 		}
  
// 		// Update status di tabel InventoriBarangKeluar menjadi 'verified'
// 		foreach ($pengajuanIds as $pengajuanId) {
// 			$pengajuan = InventoriBarangKeluarMdl::find($pengajuanId);
// 			if ($pengajuan) {
// 				$pengajuan->status_ajuan = 'verified';  // Sesuaikan dengan nilai status yang sesuai
// 				$pengajuan->save();
// 			}
// 		}
// 		return response()->json([
// 			'status' => 'success', 
// 			'statusText' => 'Data berhasil diperbarui.'
// 		]);
// 	} catch (\Illuminate\Database\QueryException $exception) {
// 		// Menangani error dan mengembalikan respons JSON
// 		return response()->json([
// 			'status' => 'error',
// 			'statusText' => $exception->getMessage()
// 		]);
// 	}
//   }




}
