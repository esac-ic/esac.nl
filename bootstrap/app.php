<?php

use App\Http\Middleware\CheckKillswitch;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            CheckKillswitch::class,
        ]);

        // The API serves the site's own JavaScript, which authenticates with the session cookie.
        $middleware->api(prepend: [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            PreventRequestForgery::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('front-end.home'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Mailman is the only HTTP API the admin actions call; show its failures instead of a server error.
        $exceptions->render(function (RequestException|ConnectionException $e, Request $request) {
            $message = 'Mailman failed: '.$e->getMessage();

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 502);
            }

            // A failing page would redirect back to itself, so only actions go back.
            return $request->isMethod('GET') ? null : back()->withInput($request->except('password', 'password_confirmation'))->with('error', $message);
        });
    })->create();
