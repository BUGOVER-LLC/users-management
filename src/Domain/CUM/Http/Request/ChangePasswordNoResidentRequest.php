<?php

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\ChangePasswordNoResidentDTO;
use Illuminate\Validation\Rules\Password;

class ChangePasswordNoResidentRequest extends AbstractRequest
{

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'exists:CitizenResetPasswords,code',
            ],
            'password' => [
                'required',
                'same:passwordConfirmation',
                Password::defaults(),
            ],
            'passwordConfirmation' => [
                'required'
            ],
        ];
    }

    public function toDTO(): ChangePasswordNoResidentDTO
    {
        return new ChangePasswordNoResidentDTO(
            $this->code,
            $this->password,
            $this->passwordConfirmation
        );
    }
}
