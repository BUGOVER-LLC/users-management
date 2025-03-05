<?php

namespace App\Domain\CUM\Rules;

use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\UMAC\Repository\UserRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsInUsersAndCitizens implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = app(UserRepository::class)->find($value);

        if (! $user) {
            $user = app(CitizenRepository::class)->find($value);
        }

        if (! $user) {
            $fail(__('validation.exists', ['attribute' => $attribute]));
        }
    }
}
