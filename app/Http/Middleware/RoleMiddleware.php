<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Gérer l'accès selon un ou plusieurs rôles autorisés.
     * Usage : ->middleware('role:commercial,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = auth()->user();

        // Non connecté
        if (! $user) {
            return redirect()->route('login');
        }

        // L'admin a accès partout ; sinon le rôle doit figurer parmi ceux autorisés.
        if ($user->role === 'admin' || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Accès refusé.');
    }
}
