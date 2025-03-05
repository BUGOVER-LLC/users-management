<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\PasswordChangeDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Override;

/**
 * @property int $userId
 * @property string $password
 * @property string $passwordConfirmation
 */
class PasswordChangeRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                'integer',
                'exists:Citizens,citizenId',
            ],
            'password' => [
                'required',
                'same:passwordConfirmation',
                Password::defaults(),
            ],
            'passwordConfirmation' => [
                'required',
            ],
        ];
    }

    #[Override]
    public function toDTO(): PasswordChangeDTO
    {
        return new PasswordChangeDTO(
            $this->userId,
            $this->password,
            $this->passwordConfirmation,
        );
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge([
            'userId' => (int) Auth::user()->citizenId,
        ]);
    }
}
