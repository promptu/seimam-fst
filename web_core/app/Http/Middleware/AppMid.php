<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;

use App\Library\Mylib;

class AppMid {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


  public function handle(Request $req, Closure $next, $aplikasi_id, $modul, $akses): Response {
    $msg = '';
    $redirect = '/login';

    if ($req->session()->has('user_ses')) {
      $ses = $req->session()->get('user_ses');
      if ($ses['id'] == 0) {
        $req->session()->put('user_ses.grant', ['modul'=>$modul, 'is_view'=>'Y', 'is_create'=>'Y', 'is_update'=>'Y', 'is_delete'=>'Y', 'is_verif_1'=>'Y', 'is_verif_2'=>'Y', 'is_sign'=>'Y']);
        return $next($req);
      }
      if (isset($ses['active_app'])) {
        if ($ses['active_app']['id'] == $aplikasi_id) {  
          if ($modul == 'Dash') {
            return $next($req);
          }        
          $qcek = DB::table('aplikasi_menu as am')->selectRaw('am.modul, ara.is_view, ara.is_create, ara.is_update, ara.is_delete, ara.is_verif_1, ara.is_verif_2, ara.is_sign')
            ->leftJoin('role_akses as ara', 'am.id', '=', 'ara.aplikasi_menu_id')
            ->where('am.aplikasi_id', $aplikasi_id)
            ->where('am.modul', $modul)
            ->where('ara.role_id', $ses['active_role']['id'])
            ->first();
          $cek = (array) $qcek;
          if (isset($cek['is_'.$akses]) && $cek['is_'.$akses] == 'Y') {
            $req->session()->put('user_ses.grant', $cek);
            return $next($req);
          }
          $msg = 'Anda tidak memiliki hak akses ke Modul ini.';
          $redirect = '/gate';
        } elseif ($aplikasi_id == "all") {
          $qcek = DB::table('aplikasi_menu as am')->selectRaw('am.modul, ara.is_view, ara.is_create, ara.is_update, ara.is_delete, ara.is_verif_1, ara.is_verif_2, ara.is_sign')
            ->leftJoin('role_akses as ara', 'am.id', '=', 'ara.aplikasi_menu_id')
            ->where('am.aplikasi_id', 1)
            ->where('am.modul', $modul)
            ->first();
          $cek = (array) $qcek;
          $req->session()->put('user_ses.grant', $cek);
          return $next($req);
        } else {
          $msg = 'Anda tidak memiliki hak akses ke Aplikasi ini.';
          $redirect = '/gate';
        }        
      } else {
        $msg = 'Silahkan pilih role terlebih dahulu.';
        $redirect = '/gate';
      }
    } else {
      $msg = 'Silahkan Sign-in terlebih dahulu.';
    }

    if ($req->expectsJson()) {
      return response()->json([ 'status'=>'info', 'statusText'=>Mylib::pesan('fail', 'custom', $msg) ]);
    } else {
      // return response()->view('error/errpage', [ 'type'=>'401', 'title'=>'Kesalahan Hak Akses', 'desc'=>'Anda tidak memiliki hak akses, hubungi Administrator.']);
      return redirect($redirect);
      // dd($msg);
    }
  }


}
