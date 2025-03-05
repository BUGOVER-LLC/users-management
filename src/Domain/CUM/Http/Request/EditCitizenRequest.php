<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\CUM\Http\DTO\EditCitizenDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Override;

/**
 * @property int $userId
 * @property string $email
 * @property string $phone
 * @property string $notificationAddress
 * @property string $password
 * @property string $passwordConfirmation
 */
class EditCitizenRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::apiCitizens->value)->check();
    }

    #[Override]
    public function rules(): array
    {
        return [
            'userId' => [
                'int',
                'exists:Citizens,citizenId',
            ],
            'email' => [
                'required',
                'email:rfc',
                "unique:Citizens,email,$this->userId,citizenId",
            ],
            'phone' => [
                'required'
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
    protected function prepareForValidation(): void
    {
        $this->merge([
            'userId' => (int) $this->route('userId'),
        ]);
    }


    #[\Override]
    public function attributes(): array
    {

        return __('citizens.registration');
    }

    #[Override]
    public function toDTO(): EditCitizenDTO
    {
        return new EditCitizenDTO(
            $this->userId,
            $this->email,
            $this->phone,
            $this->password,
            $this->passwordConfirmation,
        );
    }
}
