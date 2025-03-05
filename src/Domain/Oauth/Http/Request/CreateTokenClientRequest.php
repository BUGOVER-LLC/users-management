<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Oauth\Http\DTO\CreateTokenClientDTO;
use Illuminate\Support\Facades\Auth;

/**
 * @property string $name
 * @property string|int $client_id
 * @property ?bool $confidential
 */
final class CreateTokenClientRequest extends AbstractRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:128',
                'min:4',
            ],
            'clientId' => [
                'sometimes',
                'integer',
            ],
            'redirect' => [
                'required',
                'integer',
            ],
            'confidential' => [
                'sometimes',
                'nullable',
                'bool',
            ],
        ];
    }

    #[\Override] public function toDTO(): CreateTokenClientDTO
    {
        return new CreateTokenClientDTO(
            $this->name,
            $this->client_id,
            $this->confidential,
        );
    }
}
