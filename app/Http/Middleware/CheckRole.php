<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jika belum login, redirect ke login
        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        $userRole = auth()->user()?->role?->name ?? 'customer';
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return response()->view('errors.403', [], 403);
    }
}
