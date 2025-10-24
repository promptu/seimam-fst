<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\RoleMdl;
use \App\Models\PenggunaRoleMdl;
use \App\Models\InventoriBarangMdl;
use \App\Models\InventoriBarangMasukMdl;
use \App\Models\InventoriBarangMasukDetailMdl;
use \App\Models\InventoriStatusBarangMasukMdl;


use \App\Library\Mylib;
use \App\Library\Dropdown;

class BarangMasuk extends Controller {
  

  public function list(Request $req){
    $modul = 'InventoriBarangMasuk';

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

    $tbl = InventoriBarangMasukMdl::list($f1,$f2)->paginate($ppg);

		return view('inventori.barang_masuk', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'tanggal'=>InventoriStatusBarangMasukMdl::cmb()->get(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/barang-masuk',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Barang Masuk',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Barang Masuk','active'=>'active'],
        ],
      ],
		]);
  }



public function add(Request $req) {
    $modul = 'InventoriBarangMasuk';
    $user_ses = $req->session()->get('user_ses');
    $ctr_ses = $req->session()->get($modul);

	$validator = Validator::make($req->all(), [
		'in1'=>'required|date',
	
	  ], [
		'required' => Mylib::validasi('required'),
		'date' => Mylib::validasi('date'),  // Menambahkan validasi untuk format tanggal

	
	  ], [
		'in1'=>'Tanggal',
	  
	  ]);
		// Jika validasi gagal, kembalikan respons dengan pesan error
		if ($validator->fails()) {
		  $msg = 'Pesan Validasi :';
		  foreach ($validator->errors()->all() as $err) {
			  $msg .= '<br>- ' . $err;  // Gabungkan semua pesan error
		  }
		  return response()->json(['status' => 'info', 'statusText' => $msg]);
	  }
	  try {
    // Simpan data ke tabel inventori_barang_masuk
    $id = InventoriBarangMasukMdl::insertGetId([
        'unit_id' => $user_ses['active_role']['unit_kerja_kode'],  // Asumsikan ada user_id di user session
        'pengguna_role' => $user_ses['active_role']['id'],
        'input_by' => $user_ses['id'],
        'tanggal_input' => $req->post('in1'),  // Status default saat diajukan
    ]);

      // Kembalikan status sukses
      // return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'save')]);
	  return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'save'), 'id' => $id]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'statusText' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
public function form(Request $req) {
  $modul = 'InventoriBarangMasuk';
  $user_ses = $req->session()->get('user_ses');
  $ctr_ses = $req->session()->get($modul);
  $state = $req->segment(4);

  $f1 = $req->post('f1');
  $f2 = $req->post('f2');
  $ppg = $req->post('ppg');
  $filter = $req->post('filter');
  $act = $req->get('act');

  if ($act == 'reset') {
      $f1 = ''; $f2 = ''; $ppg = 10;
  } elseif ($filter != 'filter') {
      $f1 = $ctr_ses['f1'] ?? '';
      $f2 = $ctr_ses['f2'] ?? '';
      $ppg = $ctr_ses['ppg'] ?? 10;
  }

  $id = $req->get('id', $req->id);  // Terima ID dari add atau query string
  $req->session()->put($modul . '_id', $id);

  $req->session()->put($modul, ['f1' => $f1, 'f2' => $f2, 'ppg' => $ppg]);
  $page = $req->get('page', 1);
  $lastno = $ppg * ($page - 1);

// Ambil ID dari session
$id = $req->session()->get('InventoriBarangMasuk_id');
    
// Ambil data dari InventoriBarangMasuk
$inventoriBarangMasuk = InventoriBarangMasukMdl::find($id);
if (!$inventoriBarangMasuk) {
    return redirect()->back()->with('error', 'Data tidak ditemukan');
}
// Ambil detail barang Masuk
$tbl = InventoriBarangMasukDetailMdl::getByid($id)->paginate($ppg);

  if (!$tbl) {
      return redirect($user_ses['active_app']['link'].'/barang-masuk')->with('error', 'Data tidak ditemukan');
  }

  return view('inventori.barang_masuk_form', [
    'tbl' => $tbl,
      'state'=>$state,
      'var' => ['f1' => $f1, 'f2' => $f2, 'ppg' => $ppg, 'lastno' => $lastno],
      'cmb' => [
          'ppg' => Mylib::ppg(),
          'kategori' => InventoriStatusBarangMasukMdl::cmb()->get(),
          'nama_barang' => InventoriBarangMasukDetailMdl::namaBarang()->get(),
          'is_aktif' => Mylib::is_aktif(),
      ],
      'id' => $id,
      'user_ses' => $user_ses,
      'ctr_back' => $user_ses['active_app']['link'].'/barang-masuk',
      'ctr_path' => $user_ses['active_app']['link'].'/barang-masuk/form/edit/'.$id,
      'mylib' => Mylib::class,
      'inventoriBarangMasuk' => $inventoriBarangMasuk, // Pastikan variabel ini tersedia di view
      'page_title' => [
          'icon' => 'fas fa-list',
          'bread' => 'Barang Masuk',
          'links' => [
              ['title' => 'Pengguna', 'active' => ''],
              ['title' => 'Barang Masuk', 'active' => 'active'],
          ],
      ],
  ]);
}




public function tambah(Request $req) {
	$id = $req->get('id', $req->id);  // Terima ID dari add atau query string
	$user_ses = $req->session()->get('user_ses');
  $selectedItems = $req->post('selected_items');
	$action = $req->post('action');  // Ambil action dari tombol
  $ctr_path = $req->input('ctr_path', $user_ses['active_app']['link'].'/barang-masuk/form/edit/'.$id);


	if (empty($selectedItems)) {
		return response()->json([
			'status' => 'info', 
			'statusText' => 'Tidak ada item yang dipilih untuk diverifikasi.'
		]);
	}
  	
	try {

		// Loop melalui setiap item yang dipilih
		foreach ($selectedItems as $detailId) {
			// Dapatkan detail barang masuk berdasarkan ID
			$detail = InventoriBarangMasukDetailMdl::find($detailId);
  
			if ($detail) {
			  // Dapatkan stok barang dari tabel inventori_barang
			  $barang = InventoriBarangMdl::where('id', $detail->barang_id)->first();
  
			  if ($action == 'valid') {
				$barang->increment('jumlah_stock', $detail->jumlah);

				  $detail->status = 'valid'; 
				  $detail->submitted_at = now();
				} else if ($action == 'cancel') {
					$barang->decrement('jumlah_stock', $detail->jumlah);
					$detail->status = 'dibatalkan'; 
					$detail->updated_at = now();
				}
  
				$detail->save();
			}
		}
  
		return response()->json([
			'status' => 'success', 
			'statusText' => 'Data berhasil diperbarui.'
		]);
	} catch (\Illuminate\Database\QueryException $exception) {
		// Menangani error dan mengembalikan respons JSON
		return response()->json([
			'status' => 'error',
			'statusText' => $exception->getMessage()
		]);
	}
  }

  public function cancel(Request $req) {

    try {
      $id = $req->post('id');
      $detail = InventoriBarangMasukDetailMdl::find($id);
  
			if ($detail) {
        $barang = InventoriBarangMdl::where('id', $detail->barang_id)->first();

        if ($barang) {
          // Kurangi stok barang karena status dibatalkan
          $barang->decrement('jumlah_stock', $detail->jumlah);
                  }
          // Update status detail barang menjadi 'dibatalkan'
					$detail->status = 'dibatalkan'; 
					$detail->updated_at = now();
          $detail->save();

            return response()->json([
                'status' => 'success', 
                'statusText' => 'Barang berhasil dibatalkan.'
            ]);
        }
        return response()->json([
          'status' => 'error', 
          'statusText' => 'Data barang tidak ditemukan.'
      ]);

          } catch (\Exception $e) {
              // Tangani jika ada error
              return response()->json([
                  'status' => 'error',
                  'statusText' => 'Terjadi kesalahan: ' . $e->getMessage()
              ]);
          }
        }


public function delete(Request $req) {
    $id = $req->post('id');

    // Pengecekan: Apakah ada data di tabel inventori_barang_masuk_detail terkait dengan barang_masuk_id
    $cek = \DB::table('inventori_barang_masuk_detail')->where('barang_masuk_id', $id)->exists();
    if ($cek) {
        return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', 'Tidak bisa menghapus data, ada detail barang terkait.')]);
    }

    try {
        // Jika tidak ada data di tabel inventori_barang_masuk_detail, hapus record dari tabel inventori_barang_masuk
        $del = InventoriBarangMasukMdl::where('id', $id)->delete();

        return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'delete')]);
    } catch (\Illuminate\Database\QueryException $exception) {
        return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
    }
}

  public function deleteDetail(Request $request) {
    $id = $request->post('id');

    try {
        // Temukan detail barang masuk berdasarkan ID
        $detail = InventoriBarangMasukDetailMdl::find($id);

        if ($detail) {
            // Hapus detail dari database
            $detail->delete();

            // Kirimkan respons sukses
            return response()->json([
                'status' => 'success',
                'statusText' => 'Item berhasil dihapus.'
            ]);
        } else {
            // Kirimkan respons gagal jika detail tidak ditemukan
            return response()->json([
                'status' => 'error',
                'statusText' => 'Item tidak ditemukan.'
            ], 404);
        }
    } catch (\Exception $e) {
        // Kirimkan respons kesalahan jika terjadi pengecualian
        return response()->json([
            'status' => 'error',
            'statusText' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


public function ajukan(Request $req) {
  $id = $req->session()->get('InventoriBarangKeluar_id');

  // Validasi ID yang diambil dari sesi
  if (!$id) {
    return response()->json([
      'status' => 'error',
      'statusText' => 'ID tidak ditemukan dalam sesi.'
    ], 400);
  }

  try {
      // Update status semua detail barang keluar menjadi 'diajukan'
      InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $id)
          ->update([
            'status' => 'diajukan',
            'tanggal_ajuan' => now()
          ]);

            // Update status_ajuan di tabel inventori_barang_keluar menjadi 'diajukan'
      InventoriBarangKeluarMdl::where('id', $id)
      ->update(['status_ajuan' => 'unverified']);


      // Kirimkan respons sukses
      return response()->json([
        'status' => 'success',
        'statusText' => 'Semua detail berhasil diajukan.'
      ]);
  } catch (\Exception $e) {
      // Kirimkan respons kesalahan jika terjadi pengecualian
      return response()->json([
        'status' => 'error',
        'statusText' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
  }
}
public function save(Request $req) {
  $validator = Validator::make($req->all(), [
    'in1'=>'required|int',
    'in2'=>'required|int',

  ], [
    'required' => Mylib::validasi('required'),
    'string' => Mylib::validasi('string'),
    'numeric' => Mylib::validasi('numeric'),
    'alpha' => Mylib::validasi('alpha'),

  ], [
    'in1'=>'Barang',
    'in2'=>'Jumlah',
  
  ]);
    // Jika validasi gagal, kembalikan respons dengan pesan error
    if ($validator->fails()) {
      $msg = 'Pesan Validasi :';
      foreach ($validator->errors()->all() as $err) {
          $msg .= '<br>- ' . $err;  // Gabungkan semua pesan error
      }
      return response()->json(['status' => 'info', 'statusText' => $msg]);
  }


    // Ambil stok barang berdasarkan ID barang (in1)
    $barang = InventoriBarangMdl::find($req->post('in1'));

    if (!$barang) {
        // Jika barang tidak ditemukan
        return response()->json([
            'status' => 'info',
            'statusText' => 'Barang tidak ditemukan.'
        ]);
    }

       // Cek apakah barang yang sama sudah ada di database
       $existingItem = InventoriBarangMasukDetailMdl::where('barang_masuk_id', $req->session()->get('InventoriBarangMasuk_id'))
       ->where('barang_id', $req->post('in1'))
       ->first();

          if ($existingItem) {
          // Jika item dengan barang_id yang sama sudah ada
          return response()->json([
          'status' => 'info',
          'statusText' => 'Item dengan barang yang sama sudah terdaftar.'
          ]);
          }
  try {
      // Proses penyimpanan data
      $prep = new InventoriBarangMasukDetailMdl;
      $prep->barang_masuk_id = $req->session()->get('InventoriBarangMasuk_id');  // Ambil ID dari session
      $prep->barang_id = $req->post('in1');  // Set barang ID
      $prep->jumlah = $req->post('in2');  // Set jumlah
      $prep->status = 'draft';  // Set status awal
      $prep->created_at = now();  // Timestamp created_at
      $prep->save();  // Simpan ke database

      // Set pesan sukses dan kirimkan respons JSON
      return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'save')]);

  } catch (\Illuminate\Database\QueryException $exception) {
      // Jika terjadi error saat query, kirimkan pesan error
      return response()->json([
          'status' => 'info',
          'statusText' => Mylib::pesan('fail', 'custom', $exception->errorInfo[2])  // Kirimkan pesan error dari database
      ]);
  }
}

// Method untuk mendapatkan data detail saat edit
public function get(Request $req) {
  $id = $req->post('id');
  $get = InventoriBarangMasukDetailMdl::find($id);
  if (!$get) {
      return response()->json([
          'status' => 'info',
          'statusText' => 'Data tidak ditemukan.'
      ]);
  }
  $barang = InventoriBarangMdl::find($get->barang_id);  // Ambil data barang untuk mendapatkan nama, stok, dan satuan
  return response()->json([
      'status' => 'success',
      'statusText' => 'Data dimuat.',
      'datalist' => [
          $get->id,  // ID detail
          $get->barang_masuk_id,  // ID barang masuk
          $barang->id,  // ID barang (untuk select2)
          $barang->nama,  // Nama (untuk select2)
          $get->jumlah,  // Jumlah barang masuk
          $barang->jumlah_stock,  // Jumlah stok barang
          $barang->satuan  // Satuan barang
      ]
  ]);
}

public function cmb_barang(Request $req) {
  $qw = $req->get('q');  // Ambil query dari request
  $res = [];

  // Ambil data barang berdasarkan query (menggunakan scope 'SelCmb')
  $get = InventoriBarangMdl::selCmb($qw)->get();

  // Format data untuk Select2
  foreach ($get as $c) {
      $res[] = [
          'id' => $c->id,  // ID barang
          'text' => $c->nama,  // Nama barang (ditampilkan)
          'jumlah_stock' => $c->jumlah_stock,  // Stok barang
          'satuan' => $c->satuan  // Satuan barang
      ];
  }

  // Kirim response JSON ke frontend
  return response()->json([
      'status' => 'success',
      'statusText' => 'Data dimuat.',
      'results' => $res  // Data barang untuk Select2
  ]);
}

public function update(Request $req) {
  // Validasi input
  $validator = Validator::make($req->all(), [
      'in1' => 'required|integer',
      'in2' => 'required|integer|min:1',  // Pastikan jumlah adalah bilangan positif
      'in0' => 'required|integer|exists:inventori_barang_masuk_detail,id',  // Pastikan ID ada di database
  ], [
      'required' => Mylib::validasi('required'),
      'integer' => Mylib::validasi('integer'),
      'exists' => 'Item tidak ditemukan dalam database.',
      'min' => 'Jumlah minimal adalah 1.',
  ], [
      'in1' => 'Barang',
      'in2' => 'Jumlah',
      'in0' => 'ID',
  ]);

  if ($validator->fails()) {
    $msg = 'Pesan Validasi :';
    foreach ($validator->errors()->all() as $err) {
        $msg .= '<br>- ' . $err;
    }
    return response()->json(['status' => 'info', 'statusText' => $msg]);
}

$prep = InventoriBarangMasukDetailMdl::find($req->post('in0'));
if (!$prep) {
    return response()->json([
        'status' => 'info',
        'statusText' => 'Data tidak ditemukan.'
    ]);
}

// Ambil data barang hanya jika in1 diisi
if ($req->has('in1')) {
    $barang = InventoriBarangMdl::find($req->post('in1'));

    if (!$barang) {
        return response()->json([
            'status' => 'info',
            'statusText' => 'Barang tidak ditemukan.'
        ]);
    }

  // Cek apakah barang yang sama sudah ada di database
  $existingItem = InventoriBarangMasukDetailMdl::where('barang_masuk_id', $req->session()->get('InventoriBarangMasuk_id'))
      ->where('barang_id', $req->post('in1'))
      ->where('id', '!=', $req->post('in0'))  // Pastikan ID yang diupdate berbeda
      ->first();

  if ($existingItem) {
      return response()->json([
          'status' => 'info',
          'statusText' => 'Item dengan barang yang sama sudah terdaftar.'
      ]);
  }

    // Update data barang jika ada perubahan
    $prep->barang_id = $req->post('in1');
  }
          $prep->jumlah = $req->post('in2');
          $prep->updated_at = now();
          $prep->save();

          return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'save')]);
  //         } else {
  //         return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', 'Tidak bisa mengupdate data.')]);
  //     }
  // } catch (\Illuminate\Database\QueryException $exception) {
  //     \Log::error('Update Error: '.$exception->getMessage());  // Tambahkan logging error
  //     return response()->json([
  //         'status' => 'info',
  //         'statusText' => Mylib::pesan('fail', 'custom', $exception->errorInfo[2])
  //     ]);
  }

  public function exportHtml()
  {
      $barangMasuk = InventoriBarangMasukMdl::with('details')->get();
  
      return view('inventori.barangmasuk-html', compact('barangMasuk'));
  }


}
