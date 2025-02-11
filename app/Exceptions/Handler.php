<?php

namespace App\Exceptions;

use App\Helpers\ApiResponseHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|Response
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        if ($e instanceof ValidationException) {
            return ApiResponseHelper::error('Validation failed', $e->errors(), 422);
        }

        return ApiResponseHelper::error('Server error', [$e->getMessage()], 500);
    }
}
