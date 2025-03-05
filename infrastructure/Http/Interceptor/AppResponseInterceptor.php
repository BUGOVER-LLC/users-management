<?php

declare(strict_types=1);

namespace Infrastructure\Http\Interceptor;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class AppResponseInterceptor
 *
 * @package Src\Http\Middleware\App
 */
class AppResponseInterceptor
{
    /**
     * Enable Gzip for web requests.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        if (!$response instanceof Response) {
            return $response;
        }

        if (\function_exists('gzencode') && \in_array('gzip', $request->getEncodings())) {
            $content = $response->getContent();
            if (!empty($content)) {
                $compressed = gzencode($content, 9);

                if ($compressed) {
                    $response->setContent($compressed);

                    $response->headers->add([
                        'Content-Encoding' => 'gzip',
                        'Accept-Encoding' => 'gzip, compress, br',
                        'Vary' => 'Accept-Encoding',
                        'Content-Length' => \strlen($compressed),
                    ]);
                }
            }
        }

        return $response;
    }
}
