<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use App\Http\Transformers\ResponseTransformer;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (isset($request->route()->uri)) {
            $route = explode('/', $request->route()->uri);
            if ($route[0] === 'api') {
                if ($exception instanceof \Illuminate\Validation\ValidationException) {
                    return (new ResponseTransformer)->toJson(400, 'Error Input', $exception->errors());
                } else if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = $exception->getModel();
                    $className = strtolower(last(explode('\\', $model)));
                    return (new ResponseTransformer)->toJson(400, 'Error Input', 'Data ' . $className . ' not found');
                } else {
                    return (new ResponseTransformer)->toJson(500, $exception->getMessage(), false);
                }
            }
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($exception->redirectTo() ?? "auth/login");
    }
}
