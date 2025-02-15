<?php

namespace App\Exceptions;

use App\Helpers\ApiResponseHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * @param $request
     * @param AuthenticationException $exception
     * @return JsonResponse|Response
     */
    public function unauthenticated($request, AuthenticationException $exception): JsonResponse|Response
    {
        return ApiResponseHelper::error('Validation failed', $exception, 422);
    }
}
