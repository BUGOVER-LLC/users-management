<?php

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\ForgotPasswordDTO;

class ForgotPasswordRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'exists:Citizens,email',
            ],
        ];
    }

    public function toDTO(): ForgotPasswordDTO
    {
        return new ForgotPasswordDTO(
            $this->email
        );
    }
}
