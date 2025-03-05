<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Model;

use App\Domain\UMRA\Repository\ResourceRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property Carbon|null $updatedAt
 * @property Carbon|null $createdAt
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|Resource newModelQuery()
 * @method static Builder|Resource newQuery()
 * @method static Builder|Resource query()
 * @method static Builder|Resource whereAttributeTypeDescription($value)
 * @method static Builder|Resource whereAttributeTypeId($value)
 * @method static Builder|Resource whereAttributeTypeName($value)
 * @method static Builder|Resource whereAttributeTypeValue($value)
 * @method static Builder|Resource whereCreatedAt($value)
 * @method static Builder|Resource whereUpdatedAt($value)
 * @property int $resourceId
 * @property string|null $resourceName
 * @property string|null $resourceValue
 * @property string|null $resourceDescription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Domain\UMRA\Model\Attribute> $attribute
 * @property-read int|null $attribute_count
 * @method static Builder|Resource whereResourceDescription($value)
 * @method static Builder|Resource whereResourceId($value)
 * @method static Builder|Resource whereResourceName($value)
 * @method static Builder|Resource whereResourceValue($value)
 * @property int|null $systemId
 * @method static Builder|Resource whereSystemId($value)
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: ResourceRepository::class)]
final class Resource extends ServiceModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'Resources';

    /**
     * @var string
     */
    protected $primaryKey = 'resourceId';

    /**
     * @var string
     */
    protected string $map = 'resources';

    /**
     * @var array
     */
    protected $fillable = [
        'systemId',
        'resourceName',
        'resourceValue',
        'resourceDescription',
    ];

    /**
     * @return HasMany
     */
    public function attribute(): HasMany
    {
        return $this->hasMany(Attribute::class, 'resourceId', 'resourceId');
    }
}
