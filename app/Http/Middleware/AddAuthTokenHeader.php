<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddAuthTokenHeader
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna terotentikasi
        if (auth()->check()) {
            // Dapatkan token pengguna yang saat ini login
            $token = auth()->user()->currentAccessToken();

            // Pastikan token ada sebelum mencoba untuk mengakses plainTextToken
            if ($token) {
                // Tambahkan token sebagai header otorisasi
                $request->headers->set('Authorization', 'Bearer ' . $token->plainTextToken);
            }
        }

        return $next($request);
    }
}
