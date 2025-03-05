<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\CitizenLoginDTO;
use Override;

/**
 * @property string $email
 * @property string $password
 */
class CitizenLoginRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
            ],
            'password' => [
                'required',
            ],
        ];
    }

    #[Override]
    public function toDTO(): CitizenLoginDTO
    {
        return new CitizenLoginDTO(
            $this->email,
            $this->password
        );
    }

    #[Override]
    public function attributes()
    {
        return __('auth');
    }
}
