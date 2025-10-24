<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use App\Models\InventoriBarangKeluarMdl;
use App\Models\InventoriBarangKeluarDetailMdl;
use App\Models\InventoriBarangMasukMdl;
use App\Models\InventoriBarangMasukDetailMdl;
use App\Models\UnitKerjaMdl;
use App\Models\InventoriStatusPengajuanMdl;
use App\Models\InventoriStatusPengajuanDetailMdl;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


use \App\Library\Mylib;
use \App\Library\Dropdown;

class LaporanController extends Controller
{
    public function index_barang_keluar(Request $req){
        $modul = 'InventoriBarangKeluar';

        $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);


        return view('inventori.laporan_barang_keluar', [
            'cmb'=>[
              'unit_kerja'=>UnitKerjaMdl::cmb()->get(),
              'status_pengajuan'=>InventoriStatusPengajuanMdl::cmb()->get(),
              'status_pengajuan_detail'=>InventoriStatusPengajuanDetailMdl::cmb()->get(),
            ],
            'user_ses'=>$user_ses,
            'ctr_path'=>$user_ses['active_app']['link'].'/laporan/barang-keluar',
            'mylib'=>Mylib::class,
            'page_title'=>[
              'icon'=>'fas fa-list',
              'bread'=>'Laporan',
              'links'=>[
                ['title'=>'Pengguna','active'=>''],
                ['title'=>'Laporan Barang Keluar','active'=>'active'],
              ],
            ],
              ]);
    }


    public function set_filter_barang_keluar(Request $req){
        $req->validate([
            'f1' => 'nullable', // Validasi untuk unit kerja
            'f2' => 'nullable|exists:inventori_status_pengajuan,kode', // Validasi status pengajuan
            'f3' => 'nullable|exists:inventori_status_pengajuan_detail,kode', // Validasi status pengajuan detail
            'f4_start' => 'nullable|date',
            'f4_end' => 'nullable|date|after_or_equal:f4_start',
        ]);
		$f1 = $req->post('f1');
		$f2 = $req->post('f2');
		$f3 = $req->post('f3');
        $f4_start = $req->input('f4_start');  // Tanggal mulai
        $f4_end = $req->input('f4_end');      // Tanggal selesai
        $req->session()->put('laporan', ['f1'=>$f1,'f2'=>$f2,'f3'=>$f3,'f4_start' => $f4_start, 'f4_end' => $f4_end]);
		return response()->json(['status'=>'success','statusText'=>'Filter disimpan.']);
	}


    public function barang_keluar(Request $req) {
        // Validasi input tanggal
        $req->validate([
            'f1' => 'nullable|exists:unit_kerja,id', // Validasi untuk unit kerja
            'f2' => 'nullable|exists:inventori_status_pengajuan,id', // Validasi status pengajuan
            'f3' => 'nullable|exists:inventori_status_pengajuan_detail,id', // Validasi status pengajuan detail
            'f4_start' => 'nullable|date',
            'f4_end' => 'nullable|date|after_or_equal:f4_start',
        ]);
     
    
        // Ambil filter dari session
        $filter = $req->session()->get('laporan');
        $f1 = isset($filter['f1']) ? $filter['f1'] : '';
        $f2 = isset($filter['f2']) ? $filter['f2'] : '';
        $f3 = isset($filter['f3']) ? $filter['f3'] : '';
        $f4_start = isset($filter['f4_start']) ? $filter['f4_start'] : '';
        $f4_end = isset($filter['f4_end']) ? $filter['f4_end'] : '';
    
        // Mendapatkan nama filter
        $f1nm = $f1 ? UnitKerjaMdl::find($f1)->nama : 'Semua Unit';
        $f2nm = $f2 ? InventoriStatusPengajuanMdl::find($f2)->nama : 'Semua Status';
        $f3nm = $f3 ? InventoriStatusPengajuanDetailMdl::find($f3)->nama : 'Semua Status Barang';
        // Tanggal mulai dan selesai
        $f4nm = ($f4_start && $f4_end) ? "{$f4_start} - {$f4_end}" : 'Semua Tanggal';
    
        // Query data
        $datas = InventoriBarangKeluarDetailMdl::when($f1, function($q) use  ($f1) {
            return $q->whereHas('has_barangkeluar.unitKerja', function($bu) use ($f1){
                return $bu->where('unit_id', $f1);
            });
        })->when($f2, function($q) use ($f2) {
            return $q->whereHas('has_barangkeluar.has_status_pengajuan', function($bu) use ($f2){
                return $bu->where('status_ajuan', $f2);
            });
        })->when($f3, function($q) use ($f3) {
                return $q->where('status', $f3);
        })->when($f4_start && $f4_end, function($q) use ($f4_start, $f4_end) {
                return $q->whereBetween('tanggal_verifikasi', [$f4_start, $f4_end]);
            })
        ->orderBy('barang_keluar_id')
        ->get();
    
        $col_key = ['barang_keluar_id','tanggal_verifikasi', 'unit_kerja', 'nama_barang', 'jumlah_ajuan', 'jumlah_disetujui', 'satuan', 'status', 'status_pengajuan'];
        $col_name = ['ID Pengajuan','Tanggal Verifikasi', 'Unit Kerja', 'Nama Barang', 'Jumlah Ajuan', 'Jumlah Disetujui', 'Satuan', 'Status', 'Status Pengajuan'];
    
        $tbl = [];
        foreach ($datas as $r) {
            $tbl[] = [
                'barang_keluar_id' => $r->barang_keluar_id,
                'tanggal_verifikasi' => $r->tanggal_verifikasi,
                'unit_kerja' => $r->has_barangkeluar->unitKerja->nama ?? 'Tidak Ada Unit',
                'nama_barang' => $r->has_barang->nama ?? $r->barang_id,
                'jumlah_ajuan' => $r->jumlah,
                'jumlah_disetujui' => $r->jumlah_disetujui,
                'satuan' => $r->has_barang->satuan ?? $r->barang_id,
                'status' => $r->has_statuspengajuan->nama,
                'status_pengajuan' => $r->has_barangkeluar->has_status_pengajuan->nama ?? $r->barang_keluar_id,
            ];
        }
    
        $data_view = [
            'filter' => $filter,
            'tbl' => $tbl,
            'col_key' => $col_key,
            'col_name' => $col_name,
            'col_num' => count($col_name),
            'title' => ['f1' => "$f1nm", 'f2' => $f2nm, 'f3' => $f3nm, 'f4_start' => $f4_start, 'f4_end' => $f4_end],
            'no' => 0,
        ];
    
        if ($req->tipe == 'excel') {
            return Excel::download(new LaporanHasilKip($data_view), 'Laporan_Barang_Keluar.xlsx');
        } else {
            return view('inventori.laporan_keluar_html', $data_view);
        }
    }
    
    public function index_barang_masuk(Request $req){
        $modul = 'InventoriBarangmasuk';

        $user_ses = $req->session()->get('user_ses');
		$ctr_ses = $req->session()->get($modul);


        return view('inventori.laporan_barang_masuk', [
            'cmb'=>[
              'unit_kerja'=>UnitKerjaMdl::cmb()->get(),
              'status_pengajuan'=>InventoriStatusPengajuanMdl::cmb()->get(),
              'status_pengajuan_detail'=>InventoriStatusPengajuanDetailMdl::cmb()->get(),
            ],
            'user_ses'=>$user_ses,
            'ctr_path'=>$user_ses['active_app']['link'].'/laporan/barang-masuk',
            'mylib'=>Mylib::class,
            'page_title'=>[
              'icon'=>'fas fa-list',
              'bread'=>'Laporan',
              'links'=>[
                ['title'=>'Pengguna','active'=>''],
                ['title'=>'Laporan Barang Masuk','active'=>'active'],
              ],
            ],
              ]);
    }


    public function set_filter_barang_masuk(Request $req){
        $req->validate([
            'f1' => 'nullable', // Validasi untuk unit kerja
            'f4_start' => 'nullable|date',
            'f4_end' => 'nullable|date|after_or_equal:f4_start',
        ]);
		$f1 = $req->post('f1');
        $f4_start = $req->input('f4_start');  // Tanggal mulai
        $f4_end = $req->input('f4_end');      // Tanggal selesai
        $req->session()->put('laporan', ['f1'=>$f1,'f4_start' => $f4_start, 'f4_end' => $f4_end]);
		return response()->json(['status'=>'success','statusText'=>'Filter disimpan.']);
	}


    public function barang_masuk(Request $req) {
        $req->validate([
            'f1' => 'nullable|exists:unit_kerja,id',
            'f4_start' => 'nullable|date',
            'f4_end' => 'nullable|date|after_or_equal:f4_start',
        ]);
     
    
        $filter = $req->session()->get('laporan');
        $f1 = isset($filter['f1']) ? $filter['f1'] : '';
        $f4_start = isset($filter['f4_start']) ? $filter['f4_start'] : '';
        $f4_end = isset($filter['f4_end']) ? $filter['f4_end'] : '';
    
        $f1nm = $f1 ? UnitKerjaMdl::find($f1)->nama : 'Semua Unit';
        $f4nm = ($f4_start && $f4_end) ? "{$f4_start} - {$f4_end}" : 'Semua Tanggal';
    
        $datas = InventoriBarangMasukDetailMdl::when($f1, function($q) use  ($f1) {
            return $q->whereHas('has_barangmasuk.unitKerja', function($bu) use ($f1){
                return $bu->where('unit_id', $f1);
            });
        })->when($f4_start && $f4_end, function($q) use ($f4_start, $f4_end) {
                return $q->whereBetween('submitted_at', [$f4_start, $f4_end]);
            })
        ->orderBy('barang_masuk_id')
        ->get();
    
        $col_key = ['barang_masuk_id','submitted_at', 'unit_kerja', 'nama_barang', 'jumlah', 'satuan'];
        $col_name = ['ID Barang Masuk','Tanggal Input', 'Unit Kerja', 'Nama Barang', 'Jumlah Barang', 'Satuan'];
    
        $tbl = [];
        foreach ($datas as $r) {
            $tbl[] = [
                'barang_masuk_id' => $r->barang_masuk_id,
                'unit_kerja' => $r->has_barangmasuk->unitKerja->nama ?? 'Tidak Ada Unit',
                'nama_barang' => $r->has_barang->nama ?? $r->barang_id,
                'jumlah' => $r->jumlah,
                'satuan' => $r->has_barang->satuan ?? $r->barang_id,
                'submitted_at' => $r->submitted_at,
            ];
        }
    
        $data_view = [
            'filter' => $filter,
            'tbl' => $tbl,
            'col_key' => $col_key,
            'col_name' => $col_name,
            'col_num' => count($col_name),
            'title' => ['f1' => "$f1nm", 'f4_start' => $f4_start, 'f4_end' => $f4_end],
            'no' => 0,
        ];
    
        if ($req->tipe == 'excel') {
            return Excel::download(new LaporanHasilKip($data_view), 'Laporan_Barang_Keluar.xlsx');
        } else {
            return view('inventori.laporan_masuk_html', $data_view);
        }
    }
    


    // public function exportExcel(Request $req)
    // {
    //     $barangKeluar = $this->filterLaporan($req);

    //     return Excel::download(new BarangKeluarExport($barangKeluar), 'laporan_barang_keluar.xlsx');
    // }

    // public function exportPdf(Request $req)
    // {
    //     $barangKeluar = $this->filterLaporan($req);
    //     $pdf = PDF::loadView('inventori.export-pdf', compact('barangKeluar'));

    //     return $pdf->download('laporan_barang_keluar.pdf');
    // }

    // Fungsi untuk filter laporan berdasarkan request
    // protected function filterLaporan(Request $req)
    // {
    //     $tanggal_verifikasi = $req->input('tanggal_verifikasi');
    //     $unit_id = $req->input('unit_id');
    //     $status_ajuan = $req->input('status_ajuan');

    //     return InventoriBarangKeluarMdl::with('details')
    //         ->when($tanggal_verifikasi, function($query) use ($tanggal_verifikasi) {
    //             return $query->whereDate('tanggal_verifikasi', $tanggal_verifikasi);
    //         })
    //         ->when($unit_id, function($query) use ($unit_id) {
    //             return $query->where('unit_id', $unit_id);
    //         })
    //         ->when($status_ajuan, function($query) use ($status_ajuan) {
    //             return $query->where('status_ajuan', $status_ajuan);
    //         })
    //         ->get();
    // }
}
