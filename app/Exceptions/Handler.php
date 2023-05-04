<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException; 
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use ErrorException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

 public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException && $request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ($exception instanceof RouteNotFoundException && $request->header('Authorization') === null) {
            return response()->json(['error' => 'Missing authorization token on header.'], 401);
        }

        if ($exception instanceof RouteNotFoundException && $request->header('Authorization') !== null) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }

         
        if ($exception instanceof ErrorException && strpos($exception->getMessage(), 'Trying to get property \'secret\' of non-object') !== false) {
            return response()->json(['error' => 'Install passport and update Laravel passport access and Password client sicret at env file'], 401);
        }
        

        return parent::render($request, $exception);
    }

     
}
