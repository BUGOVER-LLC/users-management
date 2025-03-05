<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\DocumentType;
use App\Domain\Micro\Http\DTO\FindDocumentDTO;
use Illuminate\Validation\Rules\Enum;
use Override;

/**
 * @property-read string $ownerUuid
 * @property-read null|string $type
 */
class FindDocumentRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'ownerUuid' => [
                'required',
                'string',
                'exists:Citizens,uuid',
            ],
            'type' => [
                'nullable',
                new Enum(DocumentType::class),
            ],
        ];
    }

    #[Override]
    public function toDTO(): object
    {
        return new FindDocumentDTO(
            ownerUuid: $this->ownerUuid,
            type: $this->type ? DocumentType::fromValue($this->type) : null,
        );
    }
}
