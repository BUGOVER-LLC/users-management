<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMRA\DTO\StoreAttributeDTO;
use App\Domain\UMRA\Model\Attribute;
use App\Domain\UMRA\Model\Resource;
use Illuminate\Support\Facades\Auth;

class EditAttributeRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'attributeId' => [
                'required',
                'int',
                'exists:' . Attribute::getTableName() . ',' . Attribute::getPrimaryName(),
            ],
            'attributeName' => [
                'required',
                'string',
            ],
            'attributeValue' => [
                'required',
                'string',
            ],
            'attributeDescription' => [
                'nullable',
                'sometimes',
                'string',
            ],
            'resourceId' => [
                'nullable',
                'sometimes',
                'int',
                'exists:' . Resource::getTableName() . ',resourceId',
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'attributeId' => (int) $this->attributeId,
            'attributeName' => trim($this->attributeName),
            'attributeValue' => trim($this->attributeValue),
        ]);
    }

    #[\Override] public function toDTO(): object
    {
        $dto = new StoreAttributeDTO(
            $this->attributeName,
            $this->attributeValue,
            $this->attributeDescription,
            $this->resourceId,
            $this->attributeId,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
