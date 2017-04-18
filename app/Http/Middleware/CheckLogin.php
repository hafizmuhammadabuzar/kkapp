<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CheckLogin {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if (!Session::has('admin_data')) {
			// return view('admin.login');
			return redirect('admin/login')->withErrors(['error' => 'You are not logged in, Please login first']);
		}
		return $next($request);
	}

}
