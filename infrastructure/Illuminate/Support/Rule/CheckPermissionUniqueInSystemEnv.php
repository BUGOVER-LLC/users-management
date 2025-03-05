<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Support\Rule;

use App\Domain\UMRP\Repository\PermissionRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckPermissionUniqueInSystemEnv implements ValidationRule
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
        $permissionRepository = app(PermissionRepository::class);

        if (str_contains(strtolower($attribute), 'name') && $permissionRepository->existsPermissionNameInSystem(
                $this->systemId,
                $value
            )) {
            $fail('Non unique permission name');

            return;
        }

        if (str_contains(strtolower($attribute), 'value') && $permissionRepository->existsPermissionValueInSystem(
                $this->systemId,
                $value
            )) {
            $fail('Non unique permission value');
        }
    }
}
