<?php

namespace App\Exceptions;

use App\Utils\HttpStatusCodeUtil;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        UnauthorizedAction::class
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

    public function render($request, Throwable $exception)
    {
        if (in_array(get_class($exception), $this->dontReport))
            return response()->json(["payload" => ['message' => $exception->getMessage()]], HttpStatusCodeUtil::BAD_REQUEST, [], JSON_INVALID_UTF8_IGNORE);
        return parent::render($request, $exception);
    }
}
