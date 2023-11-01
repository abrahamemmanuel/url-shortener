<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait CustomHttpResponseTrait
{
    public function customExceptionMessageHandler(): JsonResponse|Response
    {
        return response()->json([
            'message' => "Short URL not found or has expired",
            'success' => false,
            'data' => null,
        ], Response::HTTP_NOT_FOUND);
    }

    public function customSuccessMessageHandler(string $message, array|string $data, int $status_code): JsonResponse|Response
    {
        return response()->json([
            'message' => $message,
            'success' => true,
            'data' => $data,
        ], $status_code);
    }
}