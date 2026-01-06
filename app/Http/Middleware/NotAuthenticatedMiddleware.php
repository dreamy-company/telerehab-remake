<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class NotAuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRole = Auth::user()->role;
        if ($userRole === "admin") {
            return redirect()->route("admin.dashboard");
        }

        if ($userRole === "patient") {
            return redirect()->route("patient.dashboard");
        }

        if ($userRole === "doctor") {
            return redirect()->route("doctor.dashboard");
        }

        if ($userRole === "therapist") {
            return redirect()->route("therapist.dashboard");
        }

        return $next($request);
    }
}
