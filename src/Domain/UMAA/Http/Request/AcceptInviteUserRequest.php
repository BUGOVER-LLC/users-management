<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMAA\Http\DTO\AcceptInviteUserDTO;
use Illuminate\Support\Facades\Auth;

/**
 * @property string $email
 * @property string $token
 * @property string $password
 */
class AcceptInviteUserRequest extends AbstractRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    #[\Override] public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'exists:InvitationUsers,inviteEmail',
            ],
            'token' => [
                'required',
                'max:128',
                'min:128',
                'exists:InvitationUsers,inviteToken',
            ],
            'password' => [
                'required',
                'min:5',
                'max:200',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return parent::errorMessages();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->email,
            'token' => $this->token,
            'password' => $this->password,
        ]);
    }

    /**
     * @return AcceptInviteUserDTO
     */
    #[\Override] public function toDTO(): AcceptInviteUserDTO
    {
        return new AcceptInviteUserDTO(
            $this->email,
            $this->token,
            $this->password,
        );
    }
}
