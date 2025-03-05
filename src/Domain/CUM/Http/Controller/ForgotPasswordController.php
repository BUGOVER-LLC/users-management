<?php

namespace App\Domain\CUM\Http\Controller;

use App\Core\Enum\EmailType;
use App\Domain\CUM\Http\Request\ForgotPasswordRequest;
use App\Domain\CUM\Queue\SendMailQueue;
use App\Domain\CUM\Service\ResetCodePasswordsService;
use Infrastructure\Http\Controllers\Controller;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use Random\Randomizer;
use Symfony\Component\HttpFoundation\Response;

final class ForgotPasswordController extends Controller
{
    public function __construct(private readonly ResetCodePasswordsService $resetCodePasswordsService)
    {
    }

    #[
        Post(
            path: '/password-reset/request',
            description: "Check email for reset password",
            tags: ['Auth'],
            parameters: [
                new Parameter(
                    name: 'email',
                    description: 'Email to reset password',
                    in: 'path',
                    required: true
                )
            ],
        ),
        \OpenApi\Attributes\Response(
            response: 200,
            description: 'Հաստատման կոդը ուղղարկվել է էլ․ հասցեին։',
        )
    ]
    public function __invoke(ForgotPasswordRequest $forgotPasswordRequest)
    {
        $dto = $forgotPasswordRequest->toDTO();

        try {
            $this->resetCodePasswordsService->resetCodeDelete($dto->email);

            $code = app(Randomizer::class)->getBytesFromString('abcdefghijklmnopqrstuvwxyz0123456789', 8);

            $this->resetCodePasswordsService->createResetCodePassword($dto->email, $code);
            SendMailQueue::dispatch(EmailType::acceptCode->value, $dto->email, ['accept_code' => $code]);
        } catch (\Exception $exception) {
            logging($exception);

            return jsponse(
                [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => __('auth.wrong'),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return jsponse(
            [
                'status' => Response::HTTP_OK,
                'message' => __('auth.accept_code_send'),
            ],
            Response::HTTP_OK,
        );
    }
}
