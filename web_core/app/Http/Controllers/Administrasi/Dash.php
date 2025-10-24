<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LoginLogMdl;

class Dash extends Controller {
	
	// index
	public function index(Request $req){
		$user_ses = $req->session()->get('user_ses');
		$thismonth = date('Y-m');
		$today = date('Y-m-d');		
		$last30days = date('Y-m-01', strtotime('-30 days', strtotime($today)));
		
		$mhs_today_log = LoginLogMdl::selectRaw('count(id) as total')
			->whereNotNull('mahasiswa_nim')
			->whereRaw("login_time like '".$today."%'")
			->first();
		$mhs_thismonth_log = LoginLogMdl::selectRaw('count(id) as total')
			->whereNotNull('mahasiswa_nim')
			->whereRaw('login_time like ?',[$thismonth.'%'])
			->first();
		$peg_today_log = LoginLogMdl::selectRaw('count(id) as total')
			->whereNotNull('pegawai_id')
			->whereRaw("login_time like '".$today."%'")
			->first();
		$peg_thismonth_log = LoginLogMdl::selectRaw('count(id) as total')
			->whereNotNull('pegawai_id')
			->whereRaw('login_time like ?',[$thismonth.'%'])
			->first();
		$get_last30days = LoginLogMdl::selectRaw('count(login_log.id) as total, role.nama as role_nama, date(login_log.login_time) as login_time_ymd')
			->leftJoin('role','login_log.role_id','=','role.id')
			->whereRaw('(login_log.pegawai_id is not null or login_log.mahasiswa_nim is not null)')
			->whereRaw('login_log.login_time between ? and ?', [$last30days.' 00:00:01', $today.' 23:59:59'])
			->groupBy('role_nama', 'login_time_ymd')
			->get();

		return view('administrasi.dashboard', [
			'user_ses'=>$user_ses,
			'statistics' => [
				'mhs_today' => $mhs_today_log->total,
				'mhs_thism' => $mhs_thismonth_log->total,
				'peg_today' => $peg_today_log->total,
				'peg_thism' => $peg_thismonth_log->total,
				'last_30d' => $get_last30days,
			],
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
