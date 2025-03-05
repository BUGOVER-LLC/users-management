<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Support\Rule;

use App\Domain\UMRP\Repository\RoleRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckRoleUniqueInSystemEnv implements ValidationRule
{
    public function __construct(private readonly int $systemId)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): PotentiallyTranslatedString $fail
     * @return void
     */
    #[\Override] public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $roleRepository = app(RoleRepository::class);

        if (str_contains(strtolower($attribute), 'name') && $roleRepository->existsRoleNameInSystem(
                $this->systemId,
                $value
            )) {
            $fail('Non unique role name');

            return;
        }

        if (str_contains(strtolower($attribute), 'value') && $roleRepository->existsRoleValueInSystem(
                $this->systemId,
                $value
            )) {
            $fail('Non unique role value');
        }
    }
}
