<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Middleware\ForceJsonResponseMiddleware;
use App\Http\Exception\ApiExceptionRegistry;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(remove: [
            StartSession::class,
            ShareErrorsFromSession::class,
            ValidateCsrfToken::class,
            ForceJsonResponseMiddleware::class,
        ]);
        $middleware->alias([
            'json' => ForceJsonResponseMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, Request $request): ?JsonResponse {
            $className = get_class($e);
            $handlers = ApiExceptionRegistry::$handlers;

            if (array_key_exists($className, $handlers)) {
                $method = $handlers[$className];
                return ApiExceptionRegistry::$method($e, $request);
            }

            return response()->json([
                'status' => 500,
                'type' => class_basename($e),
                'error' => $e->getMessage(),
            ], 500);
        });

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('*')) {
                return true;
            }

            return $request->expectsJson();
        });
    })
    ->create();
