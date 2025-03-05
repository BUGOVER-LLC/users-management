<?php

declare(strict_types=1);

namespace Infrastructure\Kernel;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Infrastructure\Exceptions\GatewayTimeoutException;
use Infrastructure\Exceptions\HttpException as ApiHttpException;
use Infrastructure\Exceptions\OauthServerException;
use Infrastructure\Exceptions\ServerErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ErrorHandler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // REPORTS 💩
        $this->reportable(function (ServerErrorException $e) {
            $token = Str::random();
            Log::error($token . "\n" . $e);
            if ($e->getCode() === Response::HTTP_INTERNAL_SERVER_ERROR) {
                throw new ApiHttpException(
                    __('http-statuses.500', ['token' => $token]),
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }

            throw new ApiHttpException(
                __('http-statuses.' . $e->getCode()),
                $e->getCode(),
            );
        });
        $this->reportable(function (UnauthorizedException|AuthorizationException $e) {
            $token = Str::random();
            Log::error($token . "\n" . $e);

            throw new ApiHttpException(
                __('http-statuses.401'),
                Response::HTTP_UNAUTHORIZED,
            );
        });
        $this->reportable(function (QueryException $e) {
            $token = Str::random();
            Log::error($token . "\n" . $e);

            throw new ApiHttpException(
                __('http-statuses.500', ['token' => $token]),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        });
        $this->reportable(function (OauthServerException $e) {
            $token = Str::random();
            Log::error($token . "\n" . $e);

            throw new ApiHttpException(
                __('http-statuses.503', ['token' => $token]),
                Response::HTTP_SERVICE_UNAVAILABLE,
            );
        });
        $this->reportable(function (GatewayTimeoutException $e) {
            $token = Str::random();
            Log::error($token . "\n" . $e);

            throw new ApiHttpException(
                __('http-statuses.501', ['token' => $token]),
                Response::HTTP_NOT_IMPLEMENTED,
            );
        });

        if (!App::environment('local')) {
            $this->renderable(function (Throwable $e) {
                $message = '' !== $e->getMessage() ? $e->getMessage() : __('http-statuses.' . $e->getCode());

                return response()->json(
                    data: [
                        'status' => $e->getCode(),
                        'message' => $message,
                        'errors' => [],
                    ],
                    status: Response::HTTP_FORBIDDEN,
                );
            });
        }
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            $this->renderable(function (AuthenticationException $e, $request) {
                return jsponse(
                    data: [
                        'status' => Response::HTTP_UNAUTHORIZED,
                        'message' => __('http-statuses.401'),
                        'errors' => [],
                    ],
                    status: Response::HTTP_UNAUTHORIZED,
                );
            });

            $this->reportable(function (HttpException $e) {
                $message = Response::$statusTexts[$e->getStatusCode()];

                throw new ApiHttpException($message, $e->getStatusCode());
            });

            if ($exception instanceof \Infrastructure\Exceptions\ServerErrorException) {
                return jsponse(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return jsponse(['message' => __('http-statuses.404')], Response::HTTP_NOT_FOUND);
            }

            if ($exception instanceof NotFoundHttpException) {
                return jsponse(
                    data: [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => __('http-statuses.404'),
                    ],
                    status: Response::HTTP_NOT_FOUND,
                );
            }

            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return jsponse(['message' => __('http-statuses.404')], Response::HTTP_NOT_FOUND);
            }
        }

        return parent::render($request, $exception);
    }
}
