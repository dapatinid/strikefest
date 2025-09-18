<?php


// app/Http/Middleware/IsAdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin == true) { // Assuming a 'is_admin' column or method
            return $next($request);
        }

        // Redirect if not an admin
        return redirect('/')->with('error', 'Unauthorized access.');
    }

    // METODE DUA
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::check() || !Auth::user()->is_admin() == true) { // Assuming isAdmin() method in User model
    //         abort(403, 'Unauthorized action.');
    //     }

    //     return $next($request);
    // }
}
