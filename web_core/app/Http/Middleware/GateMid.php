<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GateMid {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response {
			if ($req->session()->has('user_ses')) {
				return $next($req);
			}

			
			if ($req->isMethod('post')) {
				if ($req->expectsJson()) {
					return response()->json([ 'status'=>'info', 'statusText'=>$msg ]);
				} else {
					return response()->view('error/errpage', [ 'type'=>'401', 'title'=>'Kesalahan Hak Akses', 'desc'=>$msg ]);
				}
			} else {
				return redirect('/login');
			}
        
    }
}
