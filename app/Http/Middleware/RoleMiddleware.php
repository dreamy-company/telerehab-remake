<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user exists AND if their role is in the allowed $roles array
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            return back()->with('alert-error', 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
