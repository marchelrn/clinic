<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Auth\Middleware\Authenticate;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Sanctum middleware
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->redirectGuestsTo(function () {
            return null; // jangan redirect ke route login
        });

    })->create();
    // ->withExceptions(function (Exceptions $exceptions) {
    //     $exceptions->render(function (NotFoundHttpException $e, Request $request) {
    //         if ($request->is('api/*')) {
    //             return ApiResponse::error('Resource not found', 404);
    //         }
    //     });
    // })