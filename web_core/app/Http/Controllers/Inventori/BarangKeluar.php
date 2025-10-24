<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Models\RoleMdl;
use \App\Models\PenggunaRoleMdl;
use \App\Models\InventoriBarangMdl;
use \App\Models\InventoriBarangKeluarMdl;
use \App\Models\InventoriBarangKeluarDetailMdl;
use \App\Models\InventoriStatusPengajuanMdl;
use \App\Models\InventoriStatusPengajuanDetailMdl;



use \App\Library\Mylib;
use \App\Library\Dropdown;

class BarangKeluar extends Controller {
  

  public function list(Request $req){
    $modul = 'InventoriBarangKeluar';

    $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);
        $user_id = $user_ses['id']; 

		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$ppg = $req->post('ppg');
		$filter = $req->post('filter');
		$act = $req->get('act');
  $current_page = $req->get('page', 1);

  if ($act == 'reset' || $current_page != 1) {
      $f1 = ''; $f2 = ''; $ppg = 10;
    } elseif ($filter != 'filter') {
			$f1 = (isset($ctr_ses['f1'])) ? $ctr_ses['f1'] : '';
			$f2 = (isset($ctr_ses['f2'])) ? $ctr_ses['f2'] : '';
			$ppg = (isset($ctr_ses['ppg'])) ? $ctr_ses['ppg'] : 10;
		}

		$req->session()->put($modul, ['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg]);

		$page = ($req->get('page')) ? $req->get('page') : 1;
		$lastno = $ppg * ($page - 1);

    $tbl = InventoriBarangKeluarMdl::list($f1,$f2)
    ->where('inventori_barang_keluar.pegawai_id', $user_id)
    ->paginate($ppg);

		return view('inventori.barang_keluar', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'kategori'=>InventoriStatusPengajuanMdl::cmb()->get(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/barang-keluar',
			'mylib'=>Mylib::class,
      'page_title'=>[
        'icon'=>'fas fa-list',
        'bread'=>'Pengajuan Barang',
        'links'=>[
          ['title'=>'Pengguna','active'=>''],
          ['title'=>'Pengajuan Barang','active'=>'active'],
        ],
      ],
		]);
  }



public function add(Request $req) {
    $modul = 'InventoriBarangKeluar';
    $user_ses = $req->session()->get('user_ses');
    $ctr_ses = $req->session()->get($modul);

    // Simpan data ke tabel inventori_barang_keluar
    $id = InventoriBarangKeluarMdl::insertGetId([
        'unit_id' => $user_ses['active_role']['unit_kerja_kode'],  // Asumsikan ada user_id di user session
        'pengguna_role' => $user_ses['active_role']['id'],
        'pegawai_id' =>  $user_ses['id'],
        'status_ajuan' =>  'draft',  // Status default saat diajukan
        'tanggal_ajuan' => now(),  // Timestamp saat ini
    ]);

    // Redirect ke fungsi form setelah data tersimpan, dengan mengirimkan id yang baru disimpan
    return redirect()->action([self::class, 'form'], ['id' => $id]);
}
public function form(Request $req) {
  $modul = 'InventoriBarangKeluar';
  $user_ses = $req->session()->get('user_ses');
  $ctr_ses = $req->session()->get($modul);
  $state = $req->segment(4);

  $f1 = $req->post('f1');
  $f2 = $req->post('f2');
  $ppg = $req->post('ppg');
  $filter = $req->post('filter');
  $act = $req->get('act');
  $current_page = $req->get('page', 1);

  if ($act == 'reset' || $current_page != 1) {
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
$id = $req->session()->get('InventoriBarangKeluar_id');
    
// Ambil data dari InventoriBarangKeluar
$inventoriBarangKeluar = InventoriBarangKeluarMdl::find($id);
if (!$inventoriBarangKeluar) {
    return redirect()->back()->with('error', 'Data tidak ditemukan');
}
// Ambil detail barang keluar
$tbl = InventoriBarangKeluarDetailMdl::getByid($id)->paginate($ppg);

  if (!$tbl) {
      return redirect($user_ses['active_app']['link'].'/barang-keluar')->with('error', 'Data tidak ditemukan');
  }

  return view('inventori.barang_keluar_form', [
    'tbl' => $tbl,
      'state'=>$state,
      'var' => ['f1' => $f1, 'f2' => $f2, 'ppg' => $ppg, 'lastno' => $lastno],
      'cmb' => [
          'ppg' => Mylib::ppg(),
          'kategori' => InventoriStatusPengajuanDetailMdl::cmb()->get(),
          'nama_barang' => InventoriBarangKeluarDetailMdl::namaBarang()->get(),
          'is_aktif' => Mylib::is_aktif(),
      ],
      'user_ses' => $user_ses,
      'ctr_back' => $user_ses['active_app']['link'].'/barang-keluar',
      'ctr_path' => $user_ses['active_app']['link'].'/barang-keluar/form/edit/'.$id,
      'mylib' => Mylib::class,
      'status_ajuan' => $inventoriBarangKeluar->status_ajuan, // Tambahkan ini
      'inventoriBarangKeluar' => $inventoriBarangKeluar, // Pastikan variabel ini tersedia di view
      'page_title' => [
          'icon' => 'fas fa-list',
          'bread' => 'Pengajuan Barang',
          'links' => [
              ['title' => 'Pengguna', 'active' => ''],
              ['title' => 'Pengajuan Barang', 'active' => 'active'],
          ],
      ],
  ]);
}




public function verif(Request $req) {
  $user_ses = $req->session()->get('user_ses');
  $selectedItems = $req->input('selected_items', []);

  if (empty($selectedItems)) {
      return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', 'Tidak ada item yang dipilih untuk diverifikasi.')]);
  }

  try {
      // Inisialisasi array untuk menyimpan ID dari pengajuan yang perlu diupdate
      $pengajuanIds = [];

      // Loop melalui setiap item yang dipilih
      foreach ($selectedItems as $detailId) {
          // Dapatkan detail barang keluar berdasarkan ID
          $detail = InventoriBarangKeluarDetailMdl::find($detailId);

          if ($detail) {
              // Kurangi stok barang di tabel inventori_barang
              InventoriBarangMdl::where('id', $detail->barang_id)
                  ->decrement('jumlah_stock', $detail->jumlah);

              // Update status menjadi 'disetujui'
              $detail->status = 'disetujui';  // Sesuaikan dengan nilai status yang sesuai
              $detail->save();

              // Simpan ID pengajuan untuk update status di tabel InventoriBarangKeluar
              if (!in_array($detail->barang_keluar_id, $pengajuanIds)) {
                  $pengajuanIds[] = $detail->barang_keluar_id;
              }
          }
      }

      // Update status di tabel InventoriBarangKeluar menjadi 'verified'
      foreach ($pengajuanIds as $pengajuanId) {
          $pengajuan = InventoriBarangKeluarMdl::find($pengajuanId);
          if ($pengajuan) {
              $pengajuan->status_ajuan = 'verified';  // Sesuaikan dengan nilai status yang sesuai
              $pengajuan->save();
          }
      }

      return redirect($user_ses['active_app']['link'].'/barang-keluar')->with('status', 'success')->with('statusText', Mylib::pesan('success', 'save'));
    } catch (\Illuminate\Database\QueryException $exception) {
        // Redirect ke halaman sebelumnya dengan pesan error
        return redirect()->back()->with('status', 'info')->with('statusText', Mylib::pesan('fail', 'custom', $exception->errorInfo[2]));
    }
}

public function delete(Request $req){
  $id = $req->post('id');

  // Cek apakah role sedang digunakan
  $cek = InventoriBarangKeluarMdl::where('id', $id)->where('status_ajuan','!=', 'draft')->first();
  if ($cek) {
      return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', 'Tidak bisa menghapus data, Pengajuan sudah diajukan.')]);
  }

  try {
      // Hapus baris terkait di tabel inventori_barang_keluar_detail
      \DB::table('inventori_barang_keluar_detail')->where('barang_keluar_id', $id)->delete();

      // Hapus record dari tabel inventori_barang_keluar
      $del = InventoriBarangKeluarMdl::where('id', $id)->delete();

      return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'delete')]);
  } catch (\Illuminate\Database\QueryException $exception) {
      return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
  }
}


  public function deleteDetail(Request $request) {
    $id = $request->post('id');

    try {
        // Temukan detail barang keluar berdasarkan ID
        $detail = InventoriBarangKeluarDetailMdl::find($id);

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

  $detailCount = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $id)->count();
  if ($detailCount === 0) {
      return response()->json([
          'status' => 'error',
          'statusText' => 'Tidak ada data barang yang bisa diajukan.'
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

public function batal_ajukan(Request $req) {
    $id = $req->session()->get('InventoriBarangKeluar_id');
  
    // Validasi ID yang diambil dari sesi
    if (!$id) {
      return response()->json([
        'status' => 'error',
        'statusText' => 'ID tidak ditemukan dalam sesi.'
      ], 400);
    }


  $statusBarangKeluar = InventoriBarangKeluarMdl::where('id', $id)->first();

// Pastikan data ditemukan sebelum mengakses status_ajuan
if (!$statusBarangKeluar) {
    return response()->json([
        'status' => 'error',
        'statusText' => 'Data pengajuan tidak ditemukan.'
    ], 404);
}

// Cek apakah status_ajuan bukan 'unverified'
if ($statusBarangKeluar->status_ajuan !== 'unverified') {
    return response()->json([
        'status' => 'error',
        'statusText' => 'Tidak bisa membatalkan, ada barang yang sudah diverifikasi.'
    ]);
}
    try {
        // Update status semua detail barang keluar menjadi 'diajukan'
        InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $id)
            ->update([
              'status' => 'draft',
              'tanggal_ajuan' => null,
              'updated_at' => now()
            ]);
  
              // Update status_ajuan di tabel inventori_barang_keluar menjadi 'diajukan'
        InventoriBarangKeluarMdl::where('id', $id)
        ->update(['status_ajuan' => 'draft']);
  
  
        // Kirimkan respons sukses
        return response()->json([
          'status' => 'success',
          'statusText' => 'Berhasil dibatalkan.'
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

    // Cek apakah stok cukup
    if ($req->post('in2') > $barang->jumlah_stock) {
        // Jika jumlah yang diminta melebihi stok yang tersedia
        return response()->json([
            'status' => 'info',
            'statusText' => 'Jumlah melebihi stok barang yang tersedia. Stok saat ini: ' . $barang->jumlah_stock
        ]);
    }

       // Cek apakah barang yang sama sudah ada di database
       $existingItem = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $req->session()->get('InventoriBarangKeluar_id'))
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
      $prep = new InventoriBarangKeluarDetailMdl;
      $prep->barang_keluar_id = $req->session()->get('InventoriBarangKeluar_id');  // Ambil ID dari session
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
  $get = InventoriBarangKeluarDetailMdl::find($id);
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
          $get->barang_keluar_id,  // ID barang keluar
          $barang->id,  // ID barang (untuk select2)
          $barang->nama,  // Nama (untuk select2)
          $get->jumlah,  // Jumlah barang keluar
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
      'in0' => 'required|integer|exists:inventori_barang_keluar_detail,id',  // Pastikan ID ada di database
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

$prep = InventoriBarangKeluarDetailMdl::find($req->post('in0'));
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


  // Cek apakah stok cukup
  if ($req->post('in2') > $barang->jumlah_stock) {
      return response()->json([
          'status' => 'info',
          'statusText' => 'Jumlah melebihi stok barang yang tersedia. Stok saat ini: ' . $barang->jumlah_stock
      ]);
  }

  // Cek apakah barang yang sama sudah ada di database
  $existingItem = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $req->session()->get('InventoriBarangKeluar_id'))
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

  }

  public function exportHtml()
  {
      $barangKeluar = InventoriBarangKeluarMdl::with('details')->get();
  
      return view('inventori.barangkeluar-html', compact('barangKeluar'));
  }


}
