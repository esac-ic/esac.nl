<?php

namespace App\Exceptions;

use App\CustomClasses\MenuSingleton;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    private $_menu;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($this->_menu === null) {
            $this->_menu = new MenuSingleton();
        }

        if (method_exists($exception, "getStatusCode")) {
            $menu = $this->_menu;
            $curPageName = 'Error';
            switch ($exception->getStatusCode()) {
                case 403:
                    return response()->view('errors.403', compact('menu', 'curPageName'), 403);
                case 404:
                    return response()->view('errors.404', compact('menu', 'curPageName'), 404);
                case 503:
                    return response()->view('errors.503', compact('menu', 'curPageName'), 503);
                default:
                    return parent::render($request, $exception);
            }
        } else {
            return parent::render($request, $exception);
        }

    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
