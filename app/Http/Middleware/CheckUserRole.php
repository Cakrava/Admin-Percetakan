<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    /**
     * Menangani permintaan masuk dan memeriksa role pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $isLogin = session()->get('isLogin');
        $role = session()->get('role');
       
        if ($isLogin == 'yes' && $role == 'user') {
            // Jika ya, alihkan ke front.index
            return redirect()->route('front.index');
        }

        // Jika tidak, lanjutkan permintaan
        return $next($request);
    }
}
