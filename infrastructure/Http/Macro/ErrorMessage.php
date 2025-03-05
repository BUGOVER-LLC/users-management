<?php

declare(strict_types=1);

namespace Infrastructure\Http\Macro;

use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Schemas\Errors\ErrorMessageSchema;
use Symfony\Component\HttpFoundation\Response;

final class ErrorMessage extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('errorMessage', function (string $message, ?int $code = null): JsonResponse {
            return response()->schema(new ErrorMessageSchema($message), $code ?? Response::HTTP_BAD_REQUEST);
        });
    }
}
