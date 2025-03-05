<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Database;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema as BaseSchema;
use Infrastructure\Illuminate\Database\Schema\Blueprint;

class Schema extends BaseSchema
{
    #[\Override]
    public static function connection($name): Builder
    {
        return static::customizedSchemaBuilder($name);
    }

    public static function customizedSchemaBuilder(string|null $name = null): Builder
    {
        /* @var Builder $builder */
        $builder = static::$app['db']->connection($name)->getSchemaBuilder();
        $builder->blueprintResolver(static fn($table, $callback) => new Blueprint($table, $callback));

        return $builder;
    }
}
