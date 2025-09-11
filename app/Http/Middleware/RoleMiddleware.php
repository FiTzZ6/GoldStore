<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Session::get('typeuser') != $role) {
            abort(403, 'Akses ditolak'); // atau redirect sesuai kebutuhan
        }
        return $next($request);
    }
}
