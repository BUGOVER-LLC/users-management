<?php

declare(strict_types=1);

namespace Infrastructure\Http\Request;

use App\Core\Abstract\AbstractRequest;
use Infrastructure\Http\DTO\DefaultPaginateDTO;
use Override;

/**
 * @property int $page
 * @property int $per_page
 * @property ?string $search
 */
class DefaultPaginateRequest extends AbstractRequest
{
    #[Override] public function rules(): array
    {
        return [
            'page' => [
                'required',
                'int',
            ],
            'per_page' => [
                'required',
                'int',
            ],
            'search' => [
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
    public function prepareForValidation(): void
    {
        $this->merge([
            'page' => (int) $this->page,
            'per_page' => (int) $this->per_page,
            'search' => (string) $this->search,
        ]);
    }

    #[Override] public function toDTO(): object
    {
        return new DefaultPaginateDTO(
            $this->page,
            $this->per_page,
            $this->search
        );
    }
}
