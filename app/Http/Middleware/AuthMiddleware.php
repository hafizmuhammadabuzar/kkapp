<?php

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('user_data')) {
            return redirect()->back()->withErrors(['error' => 'You are not logged in, Please login first']);
        }
        return $next($request);
    }
}
