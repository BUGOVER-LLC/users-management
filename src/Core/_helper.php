<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

if (!function_exists('jsponse')) {
    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return JsonResponse
     */
    function jsponse(array $data = [], int $status = 200, array $headers = [], int $options = 0): JsonResponse
    {
        return resolve(ResponseFactory::class)->json($data, $status, $headers, $options);
    }
}

if (!function_exists('logging')) {
    /**
     * @param Exception $exception
     * @param string $channel
     * @return void
     */
    function logging(Exception $exception, string $channel = 'daily'): void
    {
        // @TODO fix change object
        $message = 'micro' === $channel
            ? $exception->getResponse()?->getBody()?->getContents()
            : $exception->getMessage();

        Log::channel($channel)->warning(
            $message,
            ['file' => $exception->getFile(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]
        );
    }
}

if (!function_exists('ddApi')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed $vars
     * @return void
     */
    #[NoReturn] function ddApi(...$vars): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        http_response_code(500);

        foreach ($vars as $v) {
            Symfony\Component\VarDumper\VarDumper::dump($v);
        }

        exit(1);
    }
}
