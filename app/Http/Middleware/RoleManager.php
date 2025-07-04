<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 🧪 Debug: Confirm middleware is triggered
        dd("RoleManager middleware is running. Requested role: $role | Auth role: " . (Auth::check() ? Auth::user()->role : 'guest'));

        if (!Auth::check()) {
            return redirect()->route('login');    
        }

        $authUserRole = Auth::user()->role;

        switch($role){
            case 'admin':
                if ($authUserRole == 0) {
                    return $next($request);
                }
                break;
            case 'worker':
                if ($authUserRole == 1) {
                    return $next($request);
                }
                break;
            case 'employer':
                if ($authUserRole == 2) {
                    return $next($request);
                }
                break;
        }

        // Redirect to the proper dashboard
        switch($authUserRole){
            case 0:
                return redirect()->route('admin');
            case 1:
                return redirect()->route('worker');
            case 2:
                return redirect()->route('employer');
            default:
                return redirect()->route('dashboard');
        }
    }
}
