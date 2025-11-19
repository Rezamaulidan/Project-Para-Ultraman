<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Menangani request yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  ...$roles (Ini akan berisi 'pemilik', 'penyewa', atau 'staf')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if (in_array($user->jenis_akun, $roles)) {
        return $next($request);
    }

    // Redirect jika tidak sesuai role
    return match ($user->jenis_akun) {
        'pemilik' => redirect()->route('pemilik.home'),
        'penyewa' => redirect()->route('dashboard.booking'),
        'staf'    => redirect()->route('staff.menu'),
        default   => redirect('/'),
    };
}
}
