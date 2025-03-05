<?php

declare(strict_types=1);

namespace App\Domain\Producer\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Producer\Http\DTO\PasswordConfirmDTO;
use App\Domain\Producer\Repository\ProducerRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Override;
use RuntimeException;

/**
 * @property string $email
 * @property string $password
 * @property string $passwordConfirm
 */
class PasswordConfirmRequest extends AbstractRequest
{
    #[Override] public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
            ],
            'password' => [
                'required',
                'string',
                'max:100',
            ],
            'passwordConfirm' => [
                'nullable',
                'sometimes',
                'string',
                'max:100',
            ],
        ];
    }

    /**
     * Class BookkeepingCompanyPaginateRequest
     *
     * @package App\Http\Requests\SystemWorker
     * @method void moreValidation(Validator $validator)
     * @method bool authorize()
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function () use ($validator) {
            if ($this->passwordConfirm && 0 !== strcmp($this->password, $this->passwordConfirm)) {
                throw new RuntimeException('Password or Email invalid');
            }

            if (!$this->passwordConfirm) {
                $producer = app(ProducerRepository::class)->findByEmail($this->email);

                if (!$producer || !Hash::check($this->password, $producer->password)) {
                    throw new RuntimeException('Password or Email invalid');
                }
            }
        });
    }

    #[Override] public function toDTO(): PasswordConfirmDTO
    {
        return new PasswordConfirmDTO(
            $this->email,
            $this->password,
            $this->passwordConfirm,
        );
    }
}
