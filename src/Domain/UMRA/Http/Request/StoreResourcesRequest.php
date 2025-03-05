<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\UMRA\DTO\StoreResourceDTO;
use App\Domain\UMRA\Model\Resource;
use Illuminate\Support\Facades\Auth;

class StoreResourcesRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'resourceName' => [
                'required',
                'string',
            ],
            'resourceValue' => [
                'required',
                'string',
                'unique:' . Resource::getTableName() . ',resourceValue',
            ],
            'resourceDescription' => [
                'nullable',
                'sometimes',
                'string',
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
            'resourceName' => trim($this->resourceName),
            'resourceValue' => trim($this->resourceValue),
        ]);
    }

    #[\Override] public function toDTO(): object
    {
        $dto = new StoreResourceDTO(
            $this->resourceName,
            $this->resourceValue,
            $this->resourceDescription,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
