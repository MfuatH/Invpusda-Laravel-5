<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Jika role tidak cocok, langsung abort (bukan redirect)
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses Dilarang untuk role: ' . strtoupper($user->role));
        }

        return $next($request);
    }
}
