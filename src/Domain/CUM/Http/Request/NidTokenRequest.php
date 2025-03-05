<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\NidTokenDTO;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\UMAC\Repository\UserRepository;

/**
 * @property string $uuid
 */
class NidTokenRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $user = app(UserRepository::class)->findByUuid($value);

                    if (! $user) {
                        $user = app(CitizenRepository::class)->findByUuid($value);
                    }

                    if (! $user) {
                        $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'clientMachine' => [
                'nullable',
                'sometimes',
                'string',
            ],
        ];
    }

    public function toDTO(): object
    {
        return new NidTokenDTO(
            uuid: $this->uuid,
            tokenMachine: $this->clientMachine,
        );
    }
}
