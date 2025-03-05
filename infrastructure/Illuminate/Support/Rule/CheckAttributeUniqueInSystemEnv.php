<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Support\Rule;

use App\Domain\UMRA\Repository\AttributeRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckAttributeUniqueInSystemEnv implements ValidationRule
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
        $permissionRepository = app(AttributeRepository::class);

        if (str_contains(strtolower($attribute), 'name') && $permissionRepository->existsAttributeNameInSystem(
                $this->systemId,
                $value
            )) {
            $fail('Non unique attribute name');

            return;
        }

        if (str_contains(strtolower($attribute), 'value') && $permissionRepository->existsAttributeValueInSystem(
                $this->systemId,
                $value
            )) {
            $fail('Non unique attribute value');
        }
    }
}
