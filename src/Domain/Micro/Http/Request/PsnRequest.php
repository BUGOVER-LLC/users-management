<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AccessDocumentType;
use App\Domain\Micro\Http\DTO\PsnDTO;
use Illuminate\Validation\Rules\Enum;
use Override;

/**
 * @property int|string $documentValue
 * @property string $documentType
 */
class PsnRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'documentValue' => [
                'required',
            ],
            'documentType' => [
                'required',
                new Enum(AccessDocumentType::class),
            ],
        ];
    }

    #[Override]
    public function toDTO(): PsnDTO
    {
        return new PsnDTO(
            $this->documentValue,
            AccessDocumentType::from($this->documentType),
        );
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge([
            'documentValue' => $this->route('documentValue'),
            'documentType' => $this->route('documentType'),
        ]);
    }
}
