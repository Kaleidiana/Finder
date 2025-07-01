<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authUserRole = Auth::user()->role;

        $roleMap = [
            'admin' => 0,
            'worker' => 1,
            'employer' => 2,
        ];

        if (isset($roleMap[$role]) && $authUserRole == $roleMap[$role]) {
            return $next($request);
        }

        // Redirect to correct dashboard if user tries to access a page for the wrong role
        return match ($authUserRole) {
            0 => redirect()->route('admin'),
            1 => redirect()->route('worker'),
            2 => redirect()->route('employer'),
            default => redirect()->route('dashboard'),
        };
    }
}