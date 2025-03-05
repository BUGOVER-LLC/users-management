<?php

declare(strict_types=1);

namespace Infrastructure\Http\Macro;

use Illuminate\Http\JsonResponse;

final class NotFound extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('notFound', function (string $message): JsonResponse {
            return response()->errorMessage($message, JsonResponse::HTTP_NOT_FOUND);
        });
    }
}
