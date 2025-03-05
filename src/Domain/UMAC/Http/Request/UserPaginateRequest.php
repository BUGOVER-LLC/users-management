<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Request;

use App\Core\Enum\AuthGuard;
use App\Domain\UMAC\Http\DTO\UserPagerDTO;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Request\DefaultPaginateRequest;

/**
 * @property bool $active
 * @property string $person
 */
class UserPaginateRequest extends DefaultPaginateRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[\Override] public function rules(): array
    {
        $parent_rules = parent::rules();

        $parent_rules['active'] = [
            'sometimes',
            'bool',
        ];

        $parent_rules['person'] = [
            'required',
            'string',
        ];

        $parent_rules['roles'] = [
            'nullable',
            'array',
        ];

        return $parent_rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    #[\Override]
    public function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            'active' => filter_var($this->active, FILTER_VALIDATE_BOOLEAN),
            'person' => $this->person,
            'roles' => $this->roles[0] ? explode(',', $this->roles[0] ?? '') : [],
            // @TODO NONE RIGHT CODE, CAUSE CLIENT SIDE PARAMS STYLE
        ]);
    }

    /**
     * @return UserPagerDTO
     */
    #[\Override] public function toDTO(): UserPagerDTO
    {
        $dto = new UserPagerDTO(
            $this->page,
            $this->per_page,
            $this->search,
            $this->person,
            $this->roles,
            $this->active,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
