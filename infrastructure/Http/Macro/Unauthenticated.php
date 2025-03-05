<?php

declare(strict_types=1);

namespace Infrastructure\Http\Macro;

use Illuminate\Http\JsonResponse;

final class Unauthenticated extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('unauthenticated', fn(string $message): JsonResponse => response()->errorMessage($message, JsonResponse::HTTP_UNAUTHORIZED));
    }
}
