<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureConnectionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect('/login')->with('error', 'You must log in first!');
        }

        if (! $user->connection) {
            return redirect('/connection/'.$user->uuid)->with('error', 'Dont have a connection account yet! Register immediately');
        }

        return $next($request);
    }
}
