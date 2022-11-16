<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Collection;
use  Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

    protected function prepareJsonResponse($request, Throwable $e)
    {        

        return response()->json([
            'errors' => [
                [
                'title' => Str::title(Str::snake(class_basename(
                    $e), ' ')),
                'detail' => $e->getMessage(),
                ]
            ]
        ], $this->isHttpException($e) ? $e->getStatusCode() : 500);
    }


    protected function invalidJson($request, ValidationException
        $exception)
    {
        $errors = ( new Collection($exception->validator->errors()) )
            ->map(function ($error, $key) {                
                return [
                    'title' => 'Validation Error',
                    'detail' => str_replace($key,str_replace(".", "", strrchr($key, ".")),$error[0]),
                    'source' => [
                        'pointer' => '/' . str_replace('.', '/', $key),
                    ]
                ];
            })
            ->values();

        return response()->json([
            'errors' => $errors,
        ], $exception->status);
        
    }

    protected function unauthenticated($request,
        AuthenticationException $exception)
    {
        if($request->expectsJson()){
            return response()->json([
                'errors' => [
                    [
                        'title' => 'Unauthenticated',
                        'detail' => 'You are not authenticated',
                    ]
                ]
            ], 403);
        }

        return redirect()->guest($exception->redirectTo() ?? route('
            login'));
    }

    public function render($request, Throwable $exception)
    {
        if($exception instanceof QueryException){
            $exception = new NotFoundHttpException('Resource not found');
        }

        return parent::render($request, $exception);
    }

}
