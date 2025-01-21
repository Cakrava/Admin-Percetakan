<?php

namespace App\Http\Responses\Auth;

use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;
use Filament\Facades\Filament;

class CustomLoginResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $role = auth()->user()->role; // pastikan 'role' adalah kolom di tabel user

        if ($role === 'admin') {
            return redirect()->intended(Filament::getUrl()); // Halaman admin Filament
        }

        // Jika role bukan admin, arahkan ke halaman yang diinginkan
        return redirect()->intended('/front/index'); // Halaman user biasa
    }
}
