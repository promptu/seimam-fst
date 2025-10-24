<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends Controller {
	
	// dashboard administrasi
	public function Administrasi(Request $req){
		$user_ses = $req->session()->get('user_ses');
		return view('dashboard', [
			'user_ses'=>$user_ses,
			'page_title'=> [
				'icon'=>'<i class="fas fa-home"></i>',
				'bread'=>'Dashboard',
				'links'=>[
					['title'=>'Dashboard', 'active'=>'active'],
				]
			]
		]);
	}
}
