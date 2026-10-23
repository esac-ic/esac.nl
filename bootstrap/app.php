<?php

use App\Http\Middleware\CheckKillswitch;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // The API serves the site's own JavaScript, which authenticates with the session cookie.
        then: fn () => Route::middleware('web')->prefix('api')->name('api.')->group(base_path('routes/api.php')),
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            CheckKillswitch::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('home'));
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

            // A failing page would redirect back to itself, so pages show the error and only actions go back.
            return $request->isMethod('GET')
                ? response()->view('errors.mailman', ['message' => $message], 502)
                : back()->withInput($request->except('password', 'password_confirmation'))->with('error', $message);
        });
    })->create();
