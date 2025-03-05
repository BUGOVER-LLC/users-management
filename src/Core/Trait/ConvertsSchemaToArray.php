<?php

declare(strict_types=1);

namespace App\Core\Trait;

use App\Core\Abstract\AbstractSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

trait ConvertsSchemaToArray
{
    /**
     * @param array|Collection $collection
     * @return array|null
     */
    public static function schemas(array|Collection $collection): ?array
    {
        return collect($collection)->map(static fn($item) => static::schema($item))->toArray();
    }

    public function toArray($request): array
    {
        return $this
            ->toSchema($request)
            ->toArray();
    }

    /**
     * @param Request $request
     * @return AbstractSchema
     */
    abstract public function toSchema(Request $request): AbstractSchema;

    /**
     * @param $resource
     * @return AbstractSchema|null
     */
    public static function schema($resource): ?AbstractSchema
    {
        return $resource ? (new static($resource))->toSchema(request()) : null;
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param mixed $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource): AnonymousResourceCollection
    {
        return parent::collection($resource);
    }
}
