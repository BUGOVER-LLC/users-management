<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TGet
 * @template TSet
 */
class SetTypeCast implements CastsAttributes
{
    #[\Override] public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        return explode(',', (string) $value);
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param Model $model
     * @param  string  $key
     * @param TSet|null  $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    #[\Override] public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return implode(',', $value);
    }
}
