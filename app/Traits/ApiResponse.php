<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponse
{
    public function successResponse($data, $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    public function errorResponse($message, $code = ResponseAlias::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    public function noContent(): Response
    {
        return response()->noContent();
    }
}
