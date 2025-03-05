<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Model\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;

/**
 * Class ScopeHelpers
 *
 * @package ServiceEntity\Models
 */
trait ScopeHelpers
{
    use HasFactory;
    use Uuid;

    /**
     * @var string
     */
    protected string $map = '';

    /**
     * @return string
     */
    public static function map(): string
    {
        return (new static())->map;
    }

    /**
     * @param $status
     * @return Builder|Model|object|null
     */
    public static function getStatusId($status)
    {
        return (new static())
            ->newModelQuery()
            ->where('status', '=', $status)
            ->first([(new static())->getKeyName(), 'status'])->{(new static())->getKeyName()};
    }

    /**
     * @param $type
     * @param string $attribute
     * @return int|string
     */
    public static function getTypeId($type, string $attribute = 'type'): int|string
    {
        return (new static())
            ->newModelQuery()
            ->where($attribute, '=', $type)
            ->first([(new static())->getKeyName(), $attribute])
            ->{(new static())->getKeyName()};
    }

    /**
     * @param $class
     * @return mixed
     */
    public static function getClassId($class)
    {
        return (new static())
            ->newModelQuery()
            ->where('type', '=', $class)
            ->first([(new static())->getKeyName(), 'class'])->{(new static())->getKeyName()};
    }

    /////////////////////////////////////////////////////STATUS, TYPE, CLASS///////////////////////////////////////////

    /**
     * @return mixed
     */
    public static function getTableName(): string
    {
        return (new static())->getTable();
    }

    /**
     * @return mixed
     */
    #[Pure] public static function getPrimaryName(): string
    {
        return (new static())->getKeyName();
    }

    /**
     * @return mixed
     */
    #[Pure] public static function getFillables(): array
    {
        return (new static())->getFillable();
    }


    ///////////////////////////////////////////////////////////HELPERS/////////////////////////////////////////////////

    /**
     * @return string
     */
    #[Pure] public function getMap(): string
    {
        return (new static())->map;
    }

    /**
     * @param $query
     * @param array $values
     * @return array|void
     */
    public function scopeExcept($query, array $values = [])
    {
        $attributes = static::first();

        if (!$attributes) {
            return;
        }

        $attributes = $attributes->getAttributes();
        $diff_data = array_diff(array_keys($attributes), array_values($values));

        return $query->select($diff_data);
    }

    /**
     * @param Model $model
     * @return Collection
     */
    public function mergeAttributes(Model $model): Collection
    {
        return collect(array_merge($this->getAttributes(), $model->getAttributes()));
    }

    /**
     * @return string
     */
    public function getModelRepositoryClass(): string
    {
        $reflectionClass = new ReflectionClass(static::class);

        return collect($reflectionClass->getAttributes(ModelEntity::class))
            ->map(fn($attribute) => $attribute->getArguments())
            ->flatten()
            ->first();
    }
}
