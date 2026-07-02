<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     *
     * @var string[]
     */
    public const SUPPORTED = ['en', 'id'];

    /**
     * Apply the user's chosen locale (stored in the session) to the app.
     * The app is Indonesian-first, so 'id' is the default.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', 'id');

        if (in_array($locale, self::SUPPORTED, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
