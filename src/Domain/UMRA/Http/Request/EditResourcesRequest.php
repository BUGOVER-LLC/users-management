<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMRA\DTO\StoreResourceDTO;
use App\Domain\UMRA\Model\Resource;
use Illuminate\Support\Facades\Auth;

class EditResourcesRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'resourceId' => [
                'required',
                'int',
                'exists:' . Resource::getTableName() . ',' . Resource::getPrimaryName(),
            ],
            'resourceName' => [
                'required',
                'string',
            ],
            'resourceValue' => [
                'required',
                'string',
            ],
            'resourceDescription' => [
                'nullable',
                'sometimes',
                'string',
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'resourceId' => (int) $this->resourceId,
            'resourceValue' => trim($this->resourceValue),
            'resourceName' => trim($this->resourceName),
        ]);
    }

    #[\Override] public function toDTO(): object
    {
        $dto = new StoreResourceDTO(
            $this->resourceName,
            $this->resourceValue,
            $this->resourceDescription,
            $this->resourceId,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
