<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMRA\DTO\StoreAttributeDTO;
use App\Domain\UMRA\Model\Attribute;
use App\Domain\UMRA\Model\Resource;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Illuminate\Support\Rule\CheckAttributeUniqueInSystemEnv;

class StoreAttributeRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'attributeName' => [
                'required',
                'string',
                new CheckAttributeUniqueInSystemEnv($this->user()->currentSystemId),
            ],
            'attributeValue' => [
                'required',
                'string',
                new CheckAttributeUniqueInSystemEnv($this->user()->currentSystemId),
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

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
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
            $this->resourceId
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
