<?php

declare(strict_types=1);

namespace Infrastructure\Http\Macro;

use Illuminate\Http\JsonResponse;

final class NotFound extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('notFound', fn(string $message): JsonResponse => response()->errorMessage($message, JsonResponse::HTTP_NOT_FOUND));
    }
}
