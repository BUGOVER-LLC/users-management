<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\Producer\Http\DTO\ProfileDTO;
use Illuminate\Support\Facades\Auth;
use Override;

class ProfileUpdateRequest extends AbstractRequest
{
    public function authorize()
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[Override] public function rules(): array
    {
        return [
            'producerId' => [
                'required',
                'exists:Producers,producerId',
            ],
            'username' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                'min:3',
            ],
            'email' => [
                'required',
                'email:rfc',
                'exists:Producers,email',
            ],
            'password' => [
                'required',
                'string',
                'max:120',
                'min:5',
            ],
            'newPassword' => [
                'nullable',
                'string',
                'max:120',
                'min:5',
                'required_with:newPasswordRepeat',
                'same:newPasswordRepeat',
            ],
            'newPasswordRepeat' => [
                'nullable',
                'string',
                'max:120',
                'min:5',
            ],
        ];
    }

    /**
     * @return ProfileDTO
     */
    #[Override] public function toDTO(): ProfileDTO
    {
        return new ProfileDTO(
            id: $this->producerId,
            username: $this->username,
            email: $this->email,
            password: $this->password,
            newPassword: $this->newPassword,
        );
    }
}
