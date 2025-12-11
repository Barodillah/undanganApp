<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (session()->has('user_id')) {
            return redirect('/admin'); // Jika sudah login, arahkan ke admin
        }

        return $next($request);
    }
}
