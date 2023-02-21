<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    public function successResponse($data, $code = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json(['data' => $data], $code);
    }

    public function errorResponse($message, $code = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    public function noContent(): Response
    {
        return response()->noContent();
    }
}
