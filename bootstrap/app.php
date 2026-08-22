<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'not-authenticated' => \App\Http\Middleware\NotAuthenticatedMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(fn() => route('auth.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Kalau body request melewati post_max_size, PHP membuang seluruh $_POST
        // termasuk token CSRF. Tanpa handler ini user hanya melihat
        // "419 Page Expired" dan tidak pernah tahu penyebabnya adalah ukuran file.
        $exceptions->render(function (PostTooLargeException $e, $request) {
            $message = 'Ukuran file terlalu besar. Maksimal ' . config('upload.max_size_mb') . 'MB per file.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            // 'error-alert' adalah channel notifikasi global yang sudah dipakai
            // layouts/app.blade.php dan layouts/admin.blade.php (SweetAlert).
            return back()->with('error-alert', $message);
        });
    })->create();
