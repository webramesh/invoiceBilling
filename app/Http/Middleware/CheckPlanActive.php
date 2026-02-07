<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPlanActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->isPlanActive()) {
            return redirect()->route('pricing')->with('error', 'Your plan has expired. Please renew to continue.');
        }

        return $next($request);
    }
}
