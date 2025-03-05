<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Domain\CUM\Http\DTO\NidDTO;
use asd\NID\Requests\CallbackRequest;

/**
 * @property string $state
 * @property string $code
 */
class CustomCallbackRequest extends CallbackRequest
{
    public function toDTO(): NidDTO
    {
        return new NidDTO(
            $this->state,
            $this->code
        );
    }
}
