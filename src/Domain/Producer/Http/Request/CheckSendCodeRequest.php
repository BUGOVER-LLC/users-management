<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Producer\Http\DTO\CheckSendCodeDTO;
use Override;

/**
 * @property string $acceptCode
 * @property string $email
 * @property string $authenticator
 */
class CheckSendCodeRequest extends AbstractRequest
{
    #[Override] public function rules(): array
    {
        return [
            'acceptCode' => [
                'required',
                'string',
                'max:6',
                'min:6',
            ],
            'email' => [
                'required',
                'email:rfc',
            ],
            'authenticator' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge(['authenticator' => $this->cookie('authenticator')]);
    }

    /**
     * @return CheckSendCodeDTO
     */
    #[Override] public function toDTO(): CheckSendCodeDTO
    {
        return new CheckSendCodeDTO(
            $this->acceptCode,
            $this->email,
            $this->authenticator
        );
    }
}
