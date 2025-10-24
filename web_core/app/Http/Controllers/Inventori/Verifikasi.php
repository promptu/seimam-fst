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

class Verifikasi extends Controller {
  

  public function list(Request $req){
    $modul = 'InventoriVerifikasi';

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

    $tbl = InventoriBarangKeluarMdl::list($f1,$f2)
    ->where('status_ajuan', '!=', 'draft') // Kondisi where untuk status_ajuan
    ->paginate($ppg);

		return view('inventori.verifikasi_list', [
      'tbl'=>$tbl,
      'var'=>['f1'=>$f1, 'f2'=>$f2, 'ppg'=>$ppg, 'lastno'=>$lastno,],
      'cmb'=>[
        'ppg'=>Mylib::ppg(),
        'kategori'=>InventoriStatusPengajuanMdl::cmb()->get(),
        'is_aktif'=>Mylib::is_aktif(),
      ],
      'user_ses'=>$user_ses,
			'ctr_path'=>$user_ses['active_app']['link'].'/verifikasi',
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
            $barang->nama,  // Nama barang
            $barang->jumlah_stock,  // Stok barang
            $get->jumlah,  // Jumlah pengajuan
            $get->jumlah_disetujui,  // Jumlah disetujui
            $get->barang_keluar_id,  // ID barang keluar
            $barang->id  // ID barang (untuk select2)
      ]
  ]);
}


  public function update(Request $req) {
    $validator = Validator::make($req->all(), [
        'in1' => 'required|string',
        'in2' => 'required|integer|min:1',  // Pastikan jumlah adalah bilangan positif
        'in3' => 'required|integer',  // Pastikan jumlah adalah bilangan positif
        'in4' => 'required|integer|min:1',  // Pastikan jumlah adalah bilangan positif
        'in0' => 'required|integer|exists:inventori_barang_keluar_detail,id',  // Pastikan ID ada di database
    ], [
        'required' => Mylib::validasi('required'),
        'integer' => Mylib::validasi('integer'),
        'exists' => 'Item tidak ditemukan dalam database.',
        'min' => 'Jumlah minimal adalah 1.',
    ], [
        'in1' => 'Nama Barang',
        'in2' => 'Jumlah Stock',
        'in3' => 'Jumlah Ajuan',
        'in4' => 'Jumlah Disetujui',
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
    
    // Ambil data barang berdasarkan ID barang
    $barang = InventoriBarangMdl::find($prep->barang_id);
    if (!$barang) {
        return response()->json([
            'status' => 'info',
            'statusText' => 'Data barang tidak ditemukan.'
        ]);
    }
      // Cek apakah stok cukup
      if ($req->post('in4') > $barang->jumlah_stock) {
          return response()->json([
              'status' => 'info',
              'statusText' => 'Jumlah melebihi stok barang yang tersedia. Stok saat ini: ' . $barang->jumlah_stock
          ]);
      }
    
              $prep->jumlah_disetujui = $req->post('in4');
              $prep->updated_at = now();
              $prep->save();
    
              return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'save')]);
  }
  
    

public function form(Request $req) {
  $modul = 'InventoriVerifikasi';
  $user_ses = $req->session()->get('user_ses');
  $ctr_ses = $req->session()->get($modul);
  $state = $req->segment(4);

  $f1 = $req->post('f1');
  $f2 = $req->post('f2');
  $ppg = $req->post('ppg', 10); // Default to 10 if not set
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
$id = $req->session()->get('InventoriVerifikasi_id');
    
// Ambil data dari InventoriBarangKeluar
$inventoriBarangKeluar = InventoriBarangKeluarMdl::find($id);
if (!$inventoriBarangKeluar) {
    return redirect()->back()->with('error', 'Data tidak ditemukan');
}
// Ambil detail barang keluar
$tbl = InventoriBarangKeluarDetailMdl::getByid($id)->paginate($ppg);

  if (!$tbl) {
      return redirect($user_ses['active_app']['link'].'/verifikasi')->with('error', 'Data tidak ditemukan');
  }

  return view('inventori.verifikasi_detail', [
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
      'ctr_path' => $user_ses['active_app']['link'].'/verifikasi/form/'.$id,
      'mylib' => Mylib::class,
      'status_ajuan' => $inventoriBarangKeluar->status_ajuan, // Tambahkan ini
      'page_title' => [
          'icon' => 'fas fa-list',
          'bread' => 'Verifikasi Ajuan',
          'links' => [
              ['title' => 'Pengguna', 'active' => ''],
              ['title' => 'Verifikasi Ajuan', 'active' => 'active'],
          ],
      ],
  ]);
}



public function verif(Request $req) {
 
  $id = $req->get('id', $req->id);  // Terima ID dari add atau query string
  $user_ses = $req->session()->get('user_ses');
  $selectedItems = $req->post('selected_items');
  $action = $req->post('action');  // Ambil action dari tombol
  $ctr_path = $req->input('ctr_path', $user_ses['active_app']['link'].'/verifikasi/form/'.$id);

  if (empty($selectedItems)) {
      return response()->json([
          'status' => 'info', 
          'statusText' => 'Tidak ada item yang dipilih untuk diverifikasi.'
      ]);
  }

  // return $selectedItems;
  
  try {
      // Inisialisasi array untuk menyimpan ID dari pengajuan yang perlu diupdate
      $pengajuanIds = [];

      // Loop melalui setiap item yang dipilih
      foreach ($selectedItems as $detailId) {
          // Dapatkan detail barang keluar berdasarkan ID
          $detail = InventoriBarangKeluarDetailMdl::find($detailId);

          if ($detail) {
            // Dapatkan stok barang dari tabel inventori_barang
            $barang = InventoriBarangMdl::where('id', $detail->barang_id)->first();

            if ($action == 'approve') {
              // Cek apakah jumlah_disetujui null, jika null ambil nilai dari jumlah ajuan
              $jumlah_disetujui = $detail->jumlah_disetujui ?? $detail->jumlah;

              // Validasi: Cek apakah jumlah disetujui melebihi stok
              if ($jumlah_disetujui > $barang->jumlah_stock) {
                  // Jika stok tidak mencukupi, kembalikan pesan error
                  return response()->json([
                      'status' => 'error',
                      'statusText' => 'Jumlah disetujui melebihi stok yang tersedia untuk barang: ' . $barang->nama_barang
                  ]);
              }

                // Jika stok mencukupi, kurangi stok barang berdasarkan jumlah disetujui
                $barang->decrement('jumlah_stock', $jumlah_disetujui);

                // Update jumlah_disetujui di detail jika sebelumnya null
                $detail->jumlah_disetujui = $jumlah_disetujui;
                  // Update status menjadi 'disetujui'
                $detail->status = 'disetujui';  // Sesuaikan dengan nilai status yang sesuai
                $detail->tanggal_verifikasi = now();
              } else if ($action == 'reject') {
                  // Update status menjadi 'ditolak'
                  $detail->status = 'ditolak';  // Sesuaikan dengan nilai status yang sesuai
                  $detail->jumlah_disetujui = '0';
                  $detail->tanggal_verifikasi = now();
              }

              $detail->save();

              // Simpan ID pengajuan untuk update status di tabel InventoriBarangKeluar
              if (!in_array($detail->barang_keluar_id, $pengajuanIds)) {
                  $pengajuanIds[] = $detail->barang_keluar_id;
              }
          }
      }

       // Update status di tabel InventoriBarangKeluar berdasarkan status item
       foreach ($pengajuanIds as $pengajuanId) {
        $pengajuan = InventoriBarangKeluarMdl::find($pengajuanId);
        if ($pengajuan) {
            // Ambil semua detail barang keluar terkait
            $totalItems = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $pengajuanId)->count();
            $verifiedItems = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $pengajuanId)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->count();

            if ($totalItems === $verifiedItems) {
                // Jika semua item sudah diverifikasi (baik disetujui atau ditolak)
                $pengajuan->status_ajuan = 'verified';  // Sesuaikan dengan nilai status yang sesuai
            } else if ($verifiedItems > 0) {
                // Jika sebagian item sudah diverifikasi
                $pengajuan->status_ajuan = 'half_verified';  // Sesuaikan dengan nilai status yang sesuai
            } else {
                // Jika belum ada item yang diverifikasi
                $pengajuan->status_ajuan = 'unverified';  // Sesuaikan dengan nilai status yang sesuai
            }
            $pengajuan->verif_by = $user_ses['id'];
            $pengajuan->save();
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
      $detail = InventoriBarangKeluarDetailMdl::find($id);
  
			if ($detail) {
        $barang = InventoriBarangMdl::where('id', $detail->barang_id)->first();

        if ($barang) {
          // Kurangi stok barang karena status dibatalkan
          $barang->increment('jumlah_stock', $detail->jumlah_disetujui);
                  }
          // Update status detail barang menjadi 'dibatalkan'
                    $detail->jumlah_disetujui = null;
					$detail->status = 'diajukan'; 
					$detail->updated_at = now();
                    $detail->save();

                    $pengajuanId = $detail->barang_keluar_id;
                    $pengajuan = InventoriBarangKeluarMdl::find($pengajuanId);
                    if ($pengajuan) {
                        // Ambil semua detail barang keluar terkait
                        $totalItems = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $pengajuanId)->count();
                        $verifiedItems = InventoriBarangKeluarDetailMdl::where('barang_keluar_id', $pengajuanId)
                            ->whereIn('status', ['disetujui', 'ditolak'])
                            ->count();
        
                        if ($verifiedItems === 0) {
                            // Jika semua item kembali ke status 'diajukan'
                            $pengajuan->status_ajuan = 'unverified';
                        } else if ($verifiedItems < $totalItems) {
                            // Jika sebagian item tetap diverifikasi
                            $pengajuan->status_ajuan = 'half_verified';
                        } else {
                            // Jika semua item diverifikasi
                            $pengajuan->status_ajuan = 'verified';
                        }
        
                        $pengajuan->updated_at = now();
                        $pengajuan->save();
                    }
            return response()->json([
                'status' => 'success', 
                'statusText' => 'Verifikasi berhasil dibatalkan.'
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


// public function verif(Request $req) {
//   $user_ses = $req->session()->get('user_ses');
//   $selectedItems = $req->input('selected_items', []);
//   $action = $req->input('action');  // Ambil action dari tombol

//   if (empty($selectedItems)) {
//       return response()->json(['status' => 'info', 'statusText' => 'Tidak ada item yang dipilih untuk diverifikasi.']);
//   }

//   try {
//       $pengajuanIds = [];

//       foreach ($selectedItems as $detailId) {
//           $detail = InventoriBarangKeluarDetailMdl::find($detailId);

//           if ($detail) {
//               if ($action == 'approve') {
//                   InventoriBarangMdl::where('id', $detail->barang_id)
//                       ->decrement('jumlah_stock', $detail->jumlah);
//                   $detail->status = 'disetujui';
//               } else if ($action == 'reject') {
//                   $detail->status = 'ditolak';
//               }

//               $detail->save();

//               if (!in_array($detail->barang_keluar_id, $pengajuanIds)) {
//                   $pengajuanIds[] = $detail->barang_keluar_id;
//               }
//           }
//       }

//       foreach ($pengajuanIds as $pengajuanId) {
//           $pengajuan = InventoriBarangKeluarMdl::find($pengajuanId);
//           if ($pengajuan) {
//               $pengajuan->status_ajuan = 'verified';
//               $pengajuan->save();
//           }
//       }
//       return response()->json(['status' => 'success', 'statusText' => 'Berhasil memproses verifikasi.']);
//   } catch (\Illuminate\Database\QueryException $exception) {
//       return response()->json(['status' => 'info', 'statusText' => $exception->errorInfo[2]]);
//   }
// }



// public function delete(Request $req){
//   $id = $req->post('id');

//   // Cek apakah role sedang digunakan
//   $cek = InventoriBarangKeluarMdl::where('id', $id)->where('status_ajuan', 'verified')->first();
//   if ($cek) {
//       return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', 'Tidak bisa menghapus data, Pengajuan sudah diverifikasi.')]);
//   }

//   try {
//       // Hapus baris terkait di tabel inventori_barang_keluar_detail
//       \DB::table('inventori_barang_keluar_detail')->where('barang_keluar_id', $id)->delete();

//       // Hapus record dari tabel inventori_barang_keluar
//       $del = InventoriBarangKeluarMdl::where('id', $id)->delete();

//       return response()->json(['status' => 'success', 'statusText' => Mylib::pesan('success', 'delete')]);
//   } catch (\Illuminate\Database\QueryException $exception) {
//       return response()->json(['status' => 'info', 'statusText' => Mylib::pesan('fail', 'custom', $exception->errorInfo[2])]);
//   }
// }


//   public function deleteDetail(Request $request, $id) {
//     // Validasi jika ID yang dikirim adalah valid
//     $request->validate([
//         'id' => 'required|integer|exists:inventori_barang_keluar_detail,id',
//     ]);

//     try {
//         // Temukan detail barang keluar berdasarkan ID
//         $detail = InventoriBarangKeluarDetailMdl::find($id);

//         if ($detail) {
//             // Hapus detail dari database
//             $detail->delete();

//             // Kirimkan respons sukses
//             return response()->json([
//                 'status' => 'success',
//                 'statusText' => 'Item berhasil dihapus.'
//             ]);
//         } else {
//             // Kirimkan respons gagal jika detail tidak ditemukan
//             return response()->json([
//                 'status' => 'error',
//                 'statusText' => 'Item tidak ditemukan.'
//             ], 404);
//         }
//     } catch (\Exception $e) {
//         // Kirimkan respons kesalahan jika terjadi pengecualian
//         return response()->json([
//             'status' => 'error',
//             'statusText' => 'Terjadi kesalahan: ' . $e->getMessage()
//         ], 500);
//     }
// }

}