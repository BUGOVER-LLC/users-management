<?php

declare(strict_types=1);

namespace App\Domain\System\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\System\Http\DTO\StoreEnvironmentDTO;
use Illuminate\Support\Facades\Auth;

class StoreEnvironmentRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    #[\Override] public function rules(): array
    {
        return [
            'systemId' => [
                'required_if:name,null',
                'int',
            ],
            'name' => [
                'required_if:systemId,null',
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],
            'domain' => [
                'required_if:systemId,null',
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],
            'logo' => [
                'sometimes',
                'nullable',
//                'file',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->systemId) {
            $this->merge([
                'systemId' => (int) $this->systemId,
            ]);
        }
    }

    #[\Override] public function toDTO(): StoreEnvironmentDTO
    {
        $dto = new StoreEnvironmentDTO(
            $this->systemId,
            $this->name,
            $this->domain,
            $this->logo,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
