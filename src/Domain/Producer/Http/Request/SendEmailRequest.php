<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Producer\Http\DTO\SendEmailDTO;
use App\Domain\Producer\Model\Producer;

/**
 * @property string $email
 * @property string $authenticator
 */
class SendEmailRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'exists:' . Producer::getTableName() . ',' . 'email',
            ],
            'authenticator' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'authenticator' => $this->cookie('authenticator'),
        ]);
    }

    public function toDTO(): SendEmailDTO
    {
        return new SendEmailDTO(
            email: $this->email,
            authenticator: $this->authenticator
        );
    }
}
