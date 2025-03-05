<?php

declare(strict_types=1);

namespace Infrastructure\Http\Interceptor;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class ApiResponseInterceptor
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /* @var JsonResponse $response */
        $response = $next($request);

        if (method_exists($response, 'getData') && method_exists($response, 'setData')) {
            $mergedResponse = array_merge(
                $response ? collect($response->getData())->toArray() : [],
                ['status' => $response->getStatusCode()]
            );
            $response->setData($mergedResponse);
        }

        return $response;
    }
}
