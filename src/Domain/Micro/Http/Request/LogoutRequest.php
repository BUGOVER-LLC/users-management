<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Micro\Http\DTO\LogoutDTO;

class LogoutRequest extends AbstractRequest
{
    #[\Override] public function rules(): array
    {
        return [
            'jti' => [
                'required',
                'string',
            ],
        ];
    }

    #[\Override] public function toDTO(): object
    {
        return new LogoutDTO($this->jti);
    }
}
