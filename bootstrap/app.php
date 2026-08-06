<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($e instanceof \Illuminate\Session\TokenMismatchException || ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 419)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'La sesión ha expirado por inactividad. Por favor, recarga la página.'], 419);
                }

                $fallbackUrl = $request->headers->get('referer') ?: url('/');

                return redirect($fallbackUrl)
                    ->withInput($request->except('password', 'password_confirmation', '_token', '_fake_user', '_fake_pass', 'admin_password'))
                    ->withErrors(['email' => 'La sesión ha expirado por inactividad o cambio de red. Por favor, intenta iniciar sesión nuevamente.'])
                    ->with('open_modal', 'login');
            }
        });
    })->create();
