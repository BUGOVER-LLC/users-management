<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\UserTokenDTO;

/**
 * @property string $uuid
 */
class UserTokenRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'string',
                'exists:Users,uuid',
            ],
        ];
    }

    public function toDTO(): object
    {
        return new UserTokenDTO(
            $this->uuid,
        );
    }
}
