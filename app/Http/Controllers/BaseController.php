<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * @param array|null $payload
     * @param int $statusCode
     * @param string|null $message
     * @return JsonResponse
     */
    protected function response(?array $payload, int $statusCode, string $message = null)
    {
        $response = [
            "payload" => $payload,
        ];

        if ($message) {
            $response["message"] = $message;
        }

        return response()->json($response, $statusCode, [], JSON_INVALID_UTF8_IGNORE);
    }
}
