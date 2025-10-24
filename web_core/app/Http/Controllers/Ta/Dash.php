<?php

namespace App\Http\Controllers\Ta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use \App\Models\MahasiswaMdl;
use \App\Models\PeriodeMdl;
use \App\Models\TaProposalMdl;
use \App\Models\TaDataMdl;
use \App\Models\TaProposalPembimbingMdl;
use \App\Models\TaDataPembimbingMdl;
use \App\Models\TaProposalBimbinganMdl;
use \App\Models\TaDataBimbinganMdl;

class Dash extends Controller {
	
	// index
	public function index(Request $req){
		$user_ses = $req->session()->get('user_ses');
		$ket_pengguna = '';
		$dash_data = [];
		
		$active_role = $user_ses['active_role']['id'];
		if ($active_role == '2') {

			$get_mahasiswa_bimbingan_proposal = TaProposalPembimbingMdl::selectRaw('count(id) as total')
				->where('pegawai_id', $user_ses['pegawai_id'])
				->where('is_active','Y')->first();
			$mhs_bimbingan_proposal = ($get_mahasiswa_bimbingan_proposal) ? $get_mahasiswa_bimbingan_proposal->total : 0;

			$get_mamasiswa_bimbingan_ta = TaDataPembimbingMdl::selectRaw('count(id) as total')
				->where('pegawai_id', $user_ses['pegawai_id'])
				->where('is_active','Y')->first();
			$mhs_bimbingan_ta = ($get_mamasiswa_bimbingan_ta) ? $get_mamasiswa_bimbingan_ta->total : 0;

			$get_bimbingan_proposal_aktif = TaProposalBimbinganMdl::selectRaw('ta_proposal_bimbingan.*, mahasiswa.nim, mahasiswa.nama')
				->leftJoin('ta_proposal_pembimbing','ta_proposal_bimbingan.ta_proposal_pembimbing_id','=','ta_proposal_pembimbing.id')
				->leftJoin('ta_proposal','ta_proposal_pembimbing.ta_proposal_id','=','ta_proposal.id')
				->leftJoin('mahasiswa','ta_proposal.mahasiswa_nim','=','mahasiswa.nim')
				->where('ta_proposal_pembimbing.pegawai_id', $user_ses['pegawai_id'])
				->where('ta_proposal_bimbingan.status_bimbingan','aktif')
				->get();

			$get_bimbingan_aktif = TaDataBimbinganMdl::selectRaw('ta_data_bimbingan.*, mahasiswa.nim, mahasiswa.nama')
				->leftJoin('ta_data_pembimbing','ta_data_bimbingan.ta_data_pembimbing_id','=','ta_data_pembimbing.id')
				->leftJoin('ta_data','ta_data_pembimbing.ta_data_id','=','ta_data.id')
				->leftJoin('mahasiswa','ta_data.mahasiswa_nim','=','mahasiswa.nim')
				->where('ta_data_pembimbing.pegawai_id', $user_ses['pegawai_id'])
				->where('ta_data_bimbingan.status_bimbingan','aktif')
				->get();

			$dash_data = [
				'tipe' => 'dosen',
				'bimbingan_proposal' => $mhs_bimbingan_proposal,
				'bimbingan_ta' => $mhs_bimbingan_ta,
				'bimbingan_proposal_aktif' => $get_bimbingan_proposal_aktif,
				'bimbingan_ta_aktif' => $get_bimbingan_aktif,
				'ket_pengguna' => $ket_pengguna,
			];

		} elseif ($active_role == '3') {

			$get_mahasiswa = MahasiswaMdl::where('nim', $user_ses['mahasiswa_nim'])->first();
			$get_periode_aktif = PeriodeMdl::where('is_aktif', 'Y')->first();
			if ($get_mahasiswa && $get_periode_aktif) {
				$periode_mahasiswa = $get_mahasiswa->periode_id;
				$periode_aktif = $get_periode_aktif->kode;

				$left_periode_aktif = substr($periode_aktif, 0, -1);
				$right_periode_aktif = substr($periode_aktif, -1);
				$left_mhs_periode = substr($periode_mahasiswa, 0, -1);
				$right_mhs_periode = substr($periode_mahasiswa, -1);

				$semester = 0;
				$selisih = $left_periode_aktif - $left_mhs_periode;
				$selisih_right = $right_periode_aktif - $right_mhs_periode;
				$semester = ($selisih * 2) + 1 + $selisih_right;
			}
			$ket_pengguna = 'Saat ini Anda berada di Semester '.$semester;

			$cek_proposal = TaProposalMdl::where('mahasiswa_nim', $user_ses['mahasiswa_nim'])
				->where('ta_status_pengajuan_kode','!=','ditolak')->first();
			if ($cek_proposal) {
				if ($cek_proposal->ta_status_pengajuan_kode == 'disetujui') {
					$step1 = [
						'judul' => 'Proposal Tugas Akhir',
						'progress' => 'Proposal Disetujui',
						'persentase' => 100,
						'link' => 'ta/proposal/form/detail/'.Crypt::encryptString($cek_proposal->id),
						'link_text' => 'Lanjutkan'
					];
				} else {
					$step1 = [
						'judul' => 'Proposal Tugas Akhir',
						'progress' => 'Menunggu Persetujuan',
						'persentase' => 50,
						'link' => 'ta/proposal/form/detail/'.Crypt::encryptString($cek_proposal->id),
						'link_text' => 'Cek'
					];
				}
			} else {			
				$step1 = [
					'judul' => 'Proposal Tugas Akhir',
					'progress' => 'Belum Pengajuan',
					'persentase' => 0,
					'link' => 'ta/proposal',
					'link_text' => 'Mulai'
				];
			}
	
			$cek_datata = TaDataMdl::where('mahasiswa_nim', $user_ses['mahasiswa_nim'])
				->where('ta_status_pengajuan_kode','!=','ditolak')->first();
			if ($cek_proposal) {
				if ($cek_datata->ta_status_pengajuan_kode == 'disetujui') {
					$step2 = [
						'judul' => 'Tugas Akhir',
						'progress' => 'TA Disetujui',
						'persentase' => 100,
						'link' => 'ta/data-ta/form/detail/'.Crypt::encryptString($cek_datata->id),
						'link_text' => 'Lanjutkan'
					];
				} else {
					$step2 = [
						'judul' => 'Tugas Akhir',
						'progress' => 'Menunggu Persetujuan',
						'persentase' => 50,
						'link' => 'ta/data-ta/form/detail/'.Crypt::encryptString($cek_datata->id),
						'link_text' => 'Cek'
					];
				}
			} else {		
				$step2 = [
					'judul' => 'Tugas Akhir',
					'progress' => 'Belum Pengajuan',
					'persentase' => 0,
					'link' => 'ta/data-ta',
					'link_text' => 'Mulai'
				];
			}

			$dash_data = [
				'tipe' => 'mahasiswa',
				'step1' => $step1,
				'step2' => $step2,
				'ket_pengguna' => $ket_pengguna,
			];

		} else {

			$get_pengajuan_proposal = TaProposalMdl::selectRaw('count(id) as total')->where('ta_status_pengajuan_kode','pengajuan')->first();
			$pengajuan_proposal = ($get_pengajuan_proposal) ? $get_pengajuan_proposal->total : 0;
			$get_pengajuan_ta = TaDataMdl::selectRaw('count(id) as total')->where('ta_status_pengajuan_kode','pengajuan')->first();
			$pengajuan_ta = ($get_pengajuan_ta) ? $get_pengajuan_ta->total : 0;

			$dash_data = [
				'tipe' => 'admin',
				'pengajuan_proposal' => $pengajuan_proposal,
				'pengajuan_ta' => $pengajuan_ta,
				'ket_pengguna' => $ket_pengguna,
			];

		}
		
		return view('ta.dashboard', [
			'user_ses'=>$user_ses,
			'dash_data' => $dash_data,
			'page_title'=>[
				'icon'=>'fas fa-tachometer-alt',
				'bread'=>'Dashboard',
				'links'=>[
					['title'=>'Dashboard','active'=>'active'],
				],
			],
		]);
	}
	
}
