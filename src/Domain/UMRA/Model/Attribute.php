<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Model;

use App\Domain\UMAC\Model\User;
use App\Domain\UMRA\Repository\AttributeRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $attributeId
 * @property string $attributeName
 * @property string $attributeValue
 * @property string $attributeDescription
 * @property Carbon $createdAt
 * @method static Builder|Attribute newModelQuery()
 * @method static Builder|Attribute newQuery()
 * @method static Builder|Attribute query()
 * @method static Builder|Attribute whereAttributeDescription($value)
 * @method static Builder|Attribute whereAttributeId($value)
 * @method static Builder|Attribute whereAttributeName($value)
 * @method static Builder|Attribute whereAttributeValue($value)
 * @method static Builder|Attribute whereCreatedAt($value)
 * @property-read Collection<int, Permission> $permission
 * @property-read int|null $permission_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static Builder|ServiceModel except(array $values = [])
 * @property int|null $typeId
 * @method static Builder|Attribute whereTypeId($value)
 * @property int $systemId
 * @property int|null $clientId
 * @method static Builder|Attribute whereClientId($value)
 * @method static Builder|Attribute whereSystemId($value)
 * @property int|null $resourceId
 * @property-read Resource|null $resource
 * @method static Builder|Attribute whereResourceId($value)
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @property string $updatedAt
 * @method static Builder|Attribute whereUpdatedAt($value)
 * @property-read Collection<int, \App\Domain\UMRA\Model\Room> $rooms
 * @property-read int|null $rooms_count
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: AttributeRepository::class)]
final class Attribute extends ServiceModel
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'Attributes';

    /**
     * @var string
     */
    protected $primaryKey = 'attributeId';

    /**
     * @var string
     */
    protected string $map = 'attribute';

    /**
     * @var array
     */
    protected $fillable = [
        'systemId',
        'clientId',
        'resourceId',
        'attributeName',
        'attributeValue',
        'attributeDescription',
    ];

    /**
     * @return BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resourceId', 'resourceId');
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'attributeId', 'attributeId');
    }

    /**
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'attributeId', 'attributeId');
    }
}
