<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Dash extends Controller {
	
	// index
	public function index(Request $req){
    $user_ses = $req->session()->get('user_ses');
		return view('inventori.dashboard', [
      'user_ses'=>$user_ses,
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
