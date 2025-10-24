<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Auth;
use App\Http\Controllers\Gate;
use App\Http\Controllers\CmbItem;

use App\Http\Controllers\Administrasi\Dash as AdministrasiDash;
use App\Http\Controllers\Administrasi\UnitKerja as AdministrasiUnitKerja;
use App\Http\Controllers\Administrasi\UnitKerjaJabatan as AdministrasiUnitKerjaJabatan;
use App\Http\Controllers\Administrasi\RoleAkses as AdministrasiRoleAkses;
use App\Http\Controllers\Administrasi\Pengguna as AdministrasiPengguna;
use App\Http\Controllers\Administrasi\Pegawai as AdministrasiPegawai;
use App\Http\Controllers\Administrasi\Role as AdministrasiRole;
use App\Http\Controllers\Administrasi\Mahasiswa as AdministrasiMahasiswa;
use App\Http\Controllers\Administrasi\TahunAkademik as AdministrasiTahunAkademik;

use App\Http\Controllers\Ta\Dash as TaDash;
use App\Http\Controllers\Ta\Proposal as TaProposal;
use App\Http\Controllers\Ta\ProposalBimbingan as TaProposalBimbingan;
use App\Http\Controllers\Ta\RuangUjian as TaRuangUjian;
use App\Http\Controllers\Ta\ProposalSyaratUjian as TaProposalSyaratUjian;
use App\Http\Controllers\Ta\ProposalJadwalUjian as TaProposalJadwalUjian;
use App\Http\Controllers\Ta\ProposalNilai as TaProposalNilai;
use App\Http\Controllers\Ta\Topik as TaTopik;

use App\Http\Controllers\Ta\DataTa as DataTa;
use App\Http\Controllers\Ta\DataTaBimbingan as DataTaBimbingan;
use App\Http\Controllers\Ta\DataTaSyaratUjian as DataTaSyaratUjian;
use App\Http\Controllers\Ta\DataTaJadwalUjian as DataTaJadwalUjian;
use App\Http\Controllers\Ta\DataTaNilai as DataTaNilai;

use App\Http\Controllers\Bak\Dashboard as BakDashboard;
use App\Http\Controllers\Bak\Syarat as BakSyarat;
use App\Http\Controllers\Bak\Layanan as BakLayanan;

use App\Http\Controllers\Dashboard;

// aplikasi ID
// 1 => administrasi aplikasi
// 
// middleware => nama_mid,aplikasi_id,modul,action
// action => view, create, update, delete, verif_1, verif_2, sign

Route::get('/', [Auth::class, 'login']);
Route::get('/sign-out', [Auth::class, 'logout']);
Route::get('/daftar', [Auth::class, 'daftar']);
Route::post('/daftar/proses', [Auth::class, 'daftar_proses']);

Route::prefix('/login')->group(function(){
	Route::get('/', [Auth::class, 'login']);
	Route::post('/check', [Auth::class, 'check']);
});

Route::prefix('/gate')->group(function(){
	Route::get('/', [Gate::class, 'view'])->middleware('gatemid');
	Route::post('/choose', [Gate::class, 'choose'])->middleware('gatemid');
});

Route::prefix('/cmb-item')->group(function(){
	Route::any('/jabatan', [CmbItem::class, 'cmb_jabatan'])->middleware('appmid:all,all,view');
	Route::any('/pegawai', [CmbItem::class, 'cmb_pegawai'])->middleware('appmid:all,all,view');
	Route::any('/mahasiswa', [CmbItem::class, 'cmb_mahasiswa'])->middleware('appmid:all,all,view');
});

Route::prefix('/administrasi')->group(function(){

	Route::get('/', [AdministrasiDash::class, 'index'])->middleware('appmid:1,Dash,view');

	Route::prefix('/unit-kerja')->group(function(){
		Route::any('/', [AdministrasiUnitKerja::class, 'list'])->middleware('appmid:1,AdministrasiUnitKerja,view');
		Route::post('/get', [AdministrasiUnitKerja::class, 'get'])->middleware('appmid:1,AdministrasiUnitKerja,view');
	});
	
	Route::prefix('/role')->group(function(){
		Route::any('/', [AdministrasiRole::class, 'list'])->middleware('appmid:1,AdministrasiRole,view');
		Route::post('/get', [AdministrasiRole::class, 'get'])->middleware('appmid:1,AdministrasiRole,view');
		Route::post('/save', [AdministrasiRole::class, 'simpan'])->middleware('appmid:1,AdministrasiRole,create');
		Route::post('/update', [AdministrasiRole::class, 'simpan'])->middleware('appmid:1,AdministrasiRole,update');
		Route::post('/delete', [AdministrasiRole::class, 'delete'])->middleware('appmid:1,AdministrasiRole,delete');
	});
	
	Route::prefix('/jabatan')->group(function(){
		Route::any('/', [AdministrasiUnitKerjaJabatan::class, 'list'])->middleware('appmid:1,AdministrasiUnitKerjaJabatan,view');
		Route::post('/get', [AdministrasiUnitKerjaJabatan::class, 'get'])->middleware('appmid:1,AdministrasiUnitKerjaJabatan,view');
		Route::post('/cmb-jabatan', [AdministrasiUnitKerjaJabatan::class, 'cmb_jabatan'])->middleware('appmid:all,AdministrasiUnitKerjaJabatan,view');
		Route::post('/save', [AdministrasiUnitKerjaJabatan::class, 'save'])->middleware('appmid:1,AdministrasiUnitKerjaJabatan,create');
		Route::post('/update', [AdministrasiUnitKerjaJabatan::class, 'save'])->middleware('appmid:1,AdministrasiUnitKerjaJabatan,update');
		Route::post('/delete', [AdministrasiUnitKerjaJabatan::class, 'delete'])->middleware('appmid:1,AdministrasiUnitKerjaJabatan,delete');
	});
	
	Route::prefix('/role-akses')->group(function(){
		Route::any('/', [AdministrasiRoleAkses::class, 'list'])->middleware('appmid:1,AdministrasiRoleAkses,view');
		Route::post('/update', [AdministrasiRoleAkses::class, 'update'])->middleware('appmid:1,AdministrasiRoleAkses,update');
	});	
	
	Route::prefix('/pengguna')->group(function(){
		Route::any('/', [AdministrasiPengguna::class, 'list'])->middleware('appmid:1,AdministrasiPengguna,view');
		Route::get('/form', [AdministrasiPengguna::class, 'form'])->middleware('appmid:1,AdministrasiPengguna,create');
		Route::get('/form/edit/{id}', [AdministrasiPengguna::class, 'form'])->middleware('appmid:1,AdministrasiPengguna,update');
		Route::get('/cmb-pegawai', [AdministrasiPengguna::class, 'cmb_pegawai'])->middleware('appmid:all,AdministrasiPengguna,view');
		Route::get('/cmb-mahasiswa', [AdministrasiPengguna::class, 'cmb_mahasiswa'])->middleware('appmid:all,AdministrasiPengguna,view');
		Route::post('/get', [AdministrasiPengguna::class, 'get'])->middleware('appmid:1,AdministrasiPengguna,view');
		Route::post('/save', [AdministrasiPengguna::class, 'save'])->middleware('appmid:1,AdministrasiPengguna,create');
		Route::post('/update', [AdministrasiPengguna::class, 'save'])->middleware('appmid:1,AdministrasiPengguna,update');
		Route::post('/delete', [AdministrasiPengguna::class, 'delete'])->middleware('appmid:1,AdministrasiPengguna,delete');
		Route::post('/add-role', [AdministrasiPengguna::class, 'add_role'])->middleware('appmid:1,AdministrasiPengguna,update');
		Route::post('/delete-role', [AdministrasiPengguna::class, 'delete_role'])->middleware('appmid:1,AdministrasiPengguna,delete');
		Route::post('/reset', [AdministrasiPengguna::class, 'reset'])->middleware('appmid:1,AdministrasiPengguna,update');
		Route::post('/loginas', [AdministrasiPengguna::class, 'loginas'])->middleware('appmid:1,AdministrasiPengguna,update');
		Route::post('/clear-attempt', [AdministrasiPengguna::class, 'clear_attempt'])->middleware('appmid:1,AdministrasiPengguna,update');
	});
	
	Route::prefix('/pegawai')->group(function(){
		Route::any('/', [AdministrasiPegawai::class, 'list'])->middleware('appmid:1,AdministrasiPegawai,view');
		Route::post('/pull-batch', [AdministrasiPegawai::class, 'pull_batch'])->middleware('appmid:1,AdministrasiPegawai,create');
		Route::post('/pull', [AdministrasiPegawai::class, 'pull'])->middleware('appmid:1,AdministrasiPegawai,update');
		Route::post('/update', [AdministrasiPegawai::class, 'update'])->middleware('appmid:1,AdministrasiPegawai,update');
		Route::get('/detail/{id}', [AdministrasiPegawai::class, 'detail'])->middleware('appmid:1,AdministrasiPegawai,view');
	});
	
	Route::prefix('/mahasiswa')->group(function(){
		Route::any('/', [AdministrasiMahasiswa::class, 'list'])->middleware('appmid:1,AdministrasiMahasiswa,view');
		Route::get('/detail/{id}', [AdministrasiMahasiswa::class, 'detail'])->middleware('appmid:1,AdministrasiMahasiswa,view');
		Route::post('/pull', [AdministrasiMahasiswa::class, 'pull'])->middleware('appmid:1,AdministrasiMahasiswa,update');
	});

	Route::prefix('/tahun-akademik')->group(function(){
		Route::any('/', [AdministrasiTahunAkademik::class, 'list'])->middleware('appmid:1,AdministrasiTahunAkademik,view');
		Route::post('/get', [AdministrasiTahunAkademik::class, 'get'])->middleware('appmid:1,AdministrasiTahunAkademik,view');
		Route::post('/save', [AdministrasiTahunAkademik::class, 'save'])->middleware('appmid:1,AdministrasiTahunAkademik,create');
		Route::post('/update', [AdministrasiTahunAkademik::class, 'save'])->middleware('appmid:1,AdministrasiTahunAkademik,update');
		Route::post('/delete', [AdministrasiTahunAkademik::class, 'delete'])->middleware('appmid:1,AdministrasiTahunAkademik,delete');
	});

});

Route::prefix('/ta')->group(function(){
	Route::get('/', [TaDash::class, 'index'])->middleware('appmid:2,Dash,view');  
	
		
	Route::prefix('/topik')->group(function(){
		Route::any('/', [TaTopik::class, 'list'])->middleware('appmid:2,TaTopik,view');
		Route::post('/get', [TaTopik::class, 'get'])->middleware('appmid:2,TaTopik,view');
		Route::post('/save', [TaTopik::class, 'save'])->middleware('appmid:2,TaTopik,create');
		Route::post('/update', [TaTopik::class, 'save'])->middleware('appmid:2,TaTopik,update');
		Route::post('/delete', [TaTopik::class, 'delete'])->middleware('appmid:2,TaTopik,delete');
	});
	
	Route::prefix('/proposal')->group(function(){
		Route::any('/', [TaProposal::class, 'list'])->middleware('appmid:2,TaProposal,view');
		Route::get('/form/{state}', [TaProposal::class, 'form'])->middleware('appmid:2,TaProposal,create');
		Route::get('/form/{state}/{id}', [TaProposal::class, 'form'])->middleware('appmid:2,TaProposal,update');
		Route::post('/save', [TaProposal::class, 'simpan'])->middleware('appmid:2,TaProposal,create');
		Route::post('/update', [TaProposal::class, 'simpan'])->middleware('appmid:2,TaProposal,update');
		Route::get('/cmb-mahasiswa', [TaProposal::class, 'cmb_mahasiswa'])->middleware('appmid:2,TaProposal,view');
		Route::post('/tolak', [TaProposal::class, 'tolak'])->middleware('appmid:2,TaProposal,verif_1');
		Route::post('/acc', [TaProposal::class, 'acc'])->middleware('appmid:2,TaProposal,verif_1');
		Route::get('/cmb-dosen', [TaProposal::class, 'cmb_dosen'])->middleware('appmid:2,TaProposal,view');
		Route::post('/add-pembimbing', [TaProposal::class, 'add_pembimbing'])->middleware('appmid:2,TaProposal,update');
		Route::post('/delete-pembimbing', [TaProposal::class, 'delete_pembimbing'])->middleware('appmid:2,TaProposal,update');

		Route::prefix('/bimbingan')->group(function(){
			Route::any('/dosen', [TaProposalBimbingan::class, 'list_bimbingan_dosen'])->middleware('appmid:2,TaProposalBimbingan,view');
			Route::any('/dosen/detail/{id}', [TaProposalBimbingan::class, 'detail_bimbingan_dosen'])->middleware('appmid:2,TaProposalBimbingan,view');
			Route::any('/dosen/form/{id}', [TaProposalBimbingan::class, 'detail_bimbingan_dosen'])->middleware('appmid:2,TaProposalBimbingan,update');
			Route::any('/dosen/save', [TaProposalBimbingan::class, 'save_bimbingan_dosen'])->middleware('appmid:2,TaProposalBimbingan,update');
			Route::any('/{id}', [TaProposalBimbingan::class, 'list'])->middleware('appmid:2,TaProposal,view');
			Route::any('/{id}/form/add', [TaProposalBimbingan::class, 'form'])->middleware('appmid:2,TaProposal,create');
			Route::any('/{id}/form/edit/{sid}', [TaProposalBimbingan::class, 'form'])->middleware('appmid:2,TaProposal,update');
			Route::get('/{id}/detail/{sid}', [TaProposalBimbingan::class, 'detail'])->middleware('appmid:2,TaProposal,view');
			Route::post('/{id}/save', [TaProposalBimbingan::class, 'save'])->middleware('appmid:2,TaProposal,create');
			Route::post('/{id}/update', [TaProposalBimbingan::class, 'save'])->middleware('appmid:2,TaProposal,update');
		});
		
		Route::prefix('/syarat-ujian')->group(function(){
			Route::post('/upload', [TaProposalSyaratUjian::class, 'upload_berkas'])->middleware('appmid:2,TaProposal,view');
			Route::post('/delete-file', [TaProposalSyaratUjian::class, 'delete_berkas'])->middleware('appmid:2,TaProposal,view');
			Route::post('/pengajuan-validasi', [TaProposalSyaratUjian::class, 'pengajuan_validasi'])->middleware('appmid:2,TaProposal,view');
			Route::any('/{id}', [TaProposalSyaratUjian::class, 'list_by_proposal'])->middleware('appmid:2,TaProposal,view');
		});

		Route::any('/jadwal-ujian/{id}', [TaProposalJadwalUjian::class, 'mahasiswa_detail'])->middleware('appmid:2,TaProposal,view');
		
		Route::prefix('/nilai-akhir')->group(function(){
			Route::post('/update', [TaProposalNilai::class, 'update'])->middleware('appmid:2,TaProposalNilai,update');
			Route::any('/edit/{id}', [TaProposalNilai::class, 'detail'])->middleware('appmid:2,TaProposalNilai,update');
			Route::any('/detail/{id}', [TaProposalNilai::class, 'detail'])->middleware('appmid:2,TaProposal,view');
		});
		
		Route::prefix('/set-jadwal-ujian')->group(function(){
			Route::any('/', [TaProposalJadwalUjian::class, 'list'])->middleware('appmid:2,TaProposalSetJadwalUjian,view');
			Route::any('/detail/{id}', [TaProposalJadwalUjian::class, 'detail'])->middleware('appmid:2,TaProposalSetJadwalUjian,view');
			Route::post('/update', [TaProposalJadwalUjian::class, 'update'])->middleware('appmid:2,TaProposal,update');
			Route::post('/add-penguji', [TaProposalJadwalUjian::class, 'add_penguji'])->middleware('appmid:2,TaProposal,update');
			Route::post('/delete-penguji', [TaProposalJadwalUjian::class, 'delete_penguji'])->middleware('appmid:2,TaProposal,update');
		});
	});
		
	Route::prefix('/ruang-ujian')->group(function(){
		Route::any('/', [TaRuangUjian::class, 'list'])->middleware('appmid:2,TaRuangUjian,view');
		Route::post('/get', [TaRuangUjian::class, 'get'])->middleware('appmid:2,TaRuangUjian,view');
		Route::post('/save', [TaRuangUjian::class, 'save'])->middleware('appmid:2,TaRuangUjian,create');
		Route::post('/update', [TaRuangUjian::class, 'save'])->middleware('appmid:2,TaRuangUjian,update');
		Route::post('/delete', [TaRuangUjian::class, 'delete'])->middleware('appmid:2,TaRuangUjian,delete');
	});

	Route::prefix('/syarat-ujian-proposal')->group(function(){
		Route::any('/', [TaProposalSyaratUjian::class, 'list'])->middleware('appmid:2,TaProposalSyaratUjian,view');
		Route::post('/get', [TaProposalSyaratUjian::class, 'get'])->middleware('appmid:2,TaProposalSyaratUjian,view');
		Route::post('/save', [TaProposalSyaratUjian::class, 'simpan'])->middleware('appmid:2,TaProposalSyaratUjian,create');
		Route::post('/update', [TaProposalSyaratUjian::class, 'simpan'])->middleware('appmid:2,TaProposalSyaratUjian,update');
		Route::post('/delete', [TaProposalSyaratUjian::class, 'delete'])->middleware('appmid:2,TaProposalSyaratUjian,delete');
		Route::any('/validasi', [TaProposalSyaratUjian::class, 'validasi_list'])->middleware('appmid:2,TaProposalSyaratUjian,view');
		Route::any('/validasi/detail/{id}', [TaProposalSyaratUjian::class, 'validasi_detail'])->middleware('appmid:2,TaProposalSyaratUjian,view');
		Route::any('/validasi/save', [TaProposalSyaratUjian::class, 'validasi_save'])->middleware('appmid:2,TaProposalSyaratUjian,update');
	});

	Route::prefix('/data-ta')->group(function(){
		Route::any('/', [DataTa::class, 'list'])->middleware('appmid:2,DataTa,view');
		Route::get('/form/{state}/{id}', [DataTa::class, 'form'])->middleware('appmid:2,DataTa,view');
		Route::get('/form/{state}', [DataTa::class, 'form'])->middleware('appmid:2,DataTa,view');
		Route::post('/save', [DataTa::class, 'simpan'])->middleware('appmid:2,DataTa,create');
		Route::post('/update', [DataTa::class, 'simpan'])->middleware('appmid:2,DataTa,update');
		Route::get('/cmb-mahasiswa', [DataTa::class, 'cmb_mahasiswa'])->middleware('appmid:2,DataTa,view');
		Route::get('/cmb-dosen', [DataTa::class, 'cmb_dosen'])->middleware('appmid:2,DataTa,view');
		Route::post('/tolak', [DataTa::class, 'tolak'])->middleware('appmid:2,DataTa,update');
		Route::post('/acc', [DataTa::class, 'acc'])->middleware('appmid:2,DataTa,update');
		Route::post('/add-pembimbing', [DataTa::class, 'add_pembimbing'])->middleware('appmid:2,DataTa,update');
		Route::post('/delete-pembimbing', [DataTa::class, 'delete_pembimbing'])->middleware('appmid:2,DataTa,update');

		Route::prefix('/bimbingan')->group(function(){
			Route::any('/dosen', [DataTaBimbingan::class, 'list_bimbingan_dosen'])->middleware('appmid:2,DataTaBimbingan,view');
			Route::any('/dosen/detail/{id}', [DataTaBimbingan::class, 'detail_bimbingan_dosen'])->middleware('appmid:2,DataTaBimbingan,view');
			Route::any('/dosen/form/{id}', [DataTaBimbingan::class, 'detail_bimbingan_dosen'])->middleware('appmid:2,DataTaBimbingan,update');
			Route::any('/dosen/save', [DataTaBimbingan::class, 'save_bimbingan_dosen'])->middleware('appmid:2,DataTaBimbingan,update');
			Route::any('/{id}', [DataTaBimbingan::class, 'list'])->middleware('appmid:2,DataTa,view');
			Route::any('/{id}/form/add', [DataTaBimbingan::class, 'form'])->middleware('appmid:2,DataTa,create');
			Route::any('/{id}/form/edit/{sid}', [DataTaBimbingan::class, 'form'])->middleware('appmid:2,DataTa,update');
			Route::get('/{id}/detail/{sid}', [DataTaBimbingan::class, 'detail'])->middleware('appmid:2,DataTa,view');
			Route::post('/{id}/save', [DataTaBimbingan::class, 'save'])->middleware('appmid:2,DataTa,create');
			Route::post('/{id}/update', [DataTaBimbingan::class, 'save'])->middleware('appmid:2,DataTa,update');
		});
		
		Route::prefix('/syarat-ujian')->group(function(){
			Route::post('/upload', [DataTaSyaratUjian::class, 'upload_berkas'])->middleware('appmid:2,TaProposal,view');
			Route::post('/delete-file', [DataTaSyaratUjian::class, 'delete_berkas'])->middleware('appmid:2,TaProposal,view');
			Route::post('/pengajuan-validasi', [DataTaSyaratUjian::class, 'pengajuan_validasi'])->middleware('appmid:2,TaProposal,view');
			Route::any('/{id}', [DataTaSyaratUjian::class, 'list_by_proposal'])->middleware('appmid:2,TaProposal,view');
		});

		Route::any('/jadwal-ujian-ta/mhs/{id}', [DataTaJadwalUjian::class, 'mahasiswa_detail'])->middleware('appmid:2,DataTa,view');
		
		Route::prefix('/nilai-akhir')->group(function(){
			Route::post('/update', [DataTaNilai::class, 'update'])->middleware('appmid:2,DataTaNilai,update');
			Route::any('/edit/{id}', [DataTaNilai::class, 'detail'])->middleware('appmid:2,DataTaNilai,update');
			Route::any('/detail/{id}', [DataTaNilai::class, 'detail'])->middleware('appmid:2,TaProposal,view');
		});
	});

	Route::prefix('/syarat-ujian-ta')->group(function(){
		Route::any('/', [DataTaSyaratUjian::class, 'list'])->middleware('appmid:2,DataTaSyaratUjian,view');
		Route::post('/get', [DataTaSyaratUjian::class, 'get'])->middleware('appmid:2,DataTaSyaratUjian,view');
		Route::post('/save', [DataTaSyaratUjian::class, 'simpan'])->middleware('appmid:2,DataTaSyaratUjian,create');
		Route::post('/update', [DataTaSyaratUjian::class, 'simpan'])->middleware('appmid:2,DataTaSyaratUjian,update');
		Route::post('/delete', [DataTaSyaratUjian::class, 'delete'])->middleware('appmid:2,DataTaSyaratUjian,delete');
		Route::any('/validasi', [DataTaSyaratUjian::class, 'validasi_list'])->middleware('appmid:2,DataTaSyaratUjian,view');
		Route::any('/validasi/detail/{id}', [DataTaSyaratUjian::class, 'validasi_detail'])->middleware('appmid:2,DataTaSyaratUjian,view');
		Route::any('/validasi/save', [DataTaSyaratUjian::class, 'validasi_save'])->middleware('appmid:2,DataTaSyaratUjian,update');
	});

	Route::prefix('/jadwal-ujian-ta')->group(function(){
		Route::any('/', [DataTaJadwalUjian::class, 'list'])->middleware('appmid:2,DataTaJadwalUjian,view');
		Route::any('/detail/{id}', [DataTaJadwalUjian::class, 'detail'])->middleware('appmid:2,DataTaJadwalUjian,view');
		Route::post('/update', [DataTaJadwalUjian::class, 'update'])->middleware('appmid:2,DataTa,update');
		Route::post('/add-penguji', [DataTaJadwalUjian::class, 'add_penguji'])->middleware('appmid:2,DataTa,update');
		Route::post('/delete-penguji', [DataTaJadwalUjian::class, 'delete_penguji'])->middleware('appmid:2,DataTa,update');
	});

});

Route::prefix('/bak')->group(function(){
	Route::get('/', [BakDashboard::class, 'index'])->middleware('appmid:6,Dash,view');
	
	Route::prefix('/syarat')->group(function(){
		Route::any('/', [BakSyarat::class, 'list'])->middleware('appmid:6,BakSyarat,view');
		Route::post('/get', [BakSyarat::class, 'get'])->middleware('appmid:6,BakSyarat,view');
		Route::post('/save', [BakSyarat::class, 'simpan'])->middleware('appmid:6,BakSyarat,create');
		Route::post('/update', [BakSyarat::class, 'simpan'])->middleware('appmid:6,BakSyarat,update');
		Route::post('/delete', [BakSyarat::class, 'delete'])->middleware('appmid:6,BakSyarat,delete');
	});
	
	Route::prefix('/layanan')->group(function(){
		Route::any('/', [BakLayanan::class, 'list'])->middleware('appmid:6,BakLayanan,view');
		Route::post('/get', [BakLayanan::class, 'get'])->middleware('appmid:6,BakLayanan,view');
		Route::post('/save', [BakLayanan::class, 'simpan'])->middleware('appmid:6,BakLayanan,create');
		Route::post('/update', [BakLayanan::class, 'simpan'])->middleware('appmid:6,BakLayanan,update');
		Route::post('/delete', [BakLayanan::class, 'delete'])->middleware('appmid:6,BakLayanan,delete');
		Route::get('/detail/{id}', [BakLayanan::class, 'detail'])->middleware('appmid:6,BakLayanan,view');
		Route::post('/upload', [BakLayanan::class, 'upload_berkas'])->middleware('appmid:6,BakLayanan,update');
		Route::post('/delete-berkas', [BakLayanan::class, 'delete_berkas'])->middleware('appmid:6,BakLayanan,update');
		Route::post('/pengajuan-validasi', [BakLayanan::class, 'pengajuan_validasi'])->middleware('appmid:6,BakLayanan,update');
	});
});