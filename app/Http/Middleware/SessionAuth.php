<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('auth_ok')) {
            return redirect()->route('auth.loginForm')->with('error', 'Debes iniciar sesión.');
        }

        return $next($request);
    }
}