<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

		// ini adalah fungsi tambahan untuk handle saat token expire
		public function handle($request, Closure $next) {
			if (
				$this->isReading($request) ||
				$this->runningUnitTests() ||
				$this->tokensMatch($request)
			) { return $this->addCookieToResponse($request, $next($request)); }
        
			return $request->expectsJson()
			? response()->json(["status" =>  "invalid_request", "statusText" => "The access token is invalid.", "hint" => "Token has expired"], 401)
			: redirect('/');
    }
		// akhir dari fungsi tambahan
}
