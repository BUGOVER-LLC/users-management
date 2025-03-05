<?php

declare(strict_types=1);

namespace App\Domain\CUM\Rules;

use App\Domain\CUM\Repository\CitizenRepository;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class PasswordMatches implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Set the data under validation.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!isset($this->data['email'])) {
            $fail(__('validation.match_current_password'));
            return;
        }

        $citizen = app(CitizenRepository::class)
            ->findByEmail(
                email: $this->data['email'],
                columns: ['password'],
            );

        if (!$citizen || !$citizen->password) {
            $fail(__('validation.match_current_password'));
            return;
        }

        if (!Hash::check($value, $citizen->password)) {
            $fail(__('validation.match_current_password'));
        }
    }
}
