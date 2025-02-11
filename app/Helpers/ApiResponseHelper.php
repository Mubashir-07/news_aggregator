<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    /**
     * @param string $message
     * @param $data
     * @param int $status
     * @return JsonResponse
     */
    public static function success(string $message, $data = [], int $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * @param string $message
     * @param $errors
     * @param int $status
     * @return JsonResponse
     */
    public static function error(string $message, $errors = [], int $status = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}

?>
