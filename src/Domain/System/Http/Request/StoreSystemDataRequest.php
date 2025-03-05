<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthProvider;
use App\Core\Enum\OauthClientAllowedType;
use App\Domain\System\Http\DTO\StoreSystemDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreSystemDataRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'name' => [
                'nullable',
                'sometimes',
                'string',
            ],
            'provider' => [
                'nullable',
                'sometimes',
                new Enum(AuthProvider::class),
            ],
            'domain' => [
                'nullable',
                'string',
                'url',
            ],
            'type' => [
                'required',
                'string',
                new Enum(OauthClientAllowedType::class),
            ],
        ];
    }

    #[\Override] public function toDTO(): StoreSystemDTO
    {
        $dto = new StoreSystemDTO(
            $this->name,
            $this->provider,
            $this->domain ?? '',
            $this->type
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
