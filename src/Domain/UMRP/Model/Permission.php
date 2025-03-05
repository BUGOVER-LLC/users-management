<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Model;

use App\Domain\UMAC\Model\User;
use App\Domain\UMRP\Repository\PermissionRepository;
use Database\Factories\Domain\UMRL\Model\PermissionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $permissionId
 * @property string $permissionName
 * @property string $permissionDescription
 * @property bool $permissionActive
 * @method static PermissionFactory factory($count = null, $state = [])
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission wherePermissionDescription($value)
 * @method static Builder|Permission wherePermissionId($value)
 * @method static Builder|Permission wherePermissionName($value)
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property int $active
 * @method static Builder|Permission whereActive($value)
 * @method static Builder|Permission wherePermissionActive($value)
 * @property Carbon|null $createdAt
 * @method static Builder|Permission whereCreatedAt($value)
 * @property-read Collection<int, \App\Domain\UMRA\Model\Attribute> $attributes
 * @property-read int|null $attributes_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @property string|null $permissionValue
 * @method static Builder|Permission wherePermissionValue($value)
 * @property int|null $systemId
 * @property int|null $clientId
 * @property RolePermission|null $pivot
 * @method static Builder|Permission whereClientId($value)
 * @method static Builder|Permission whereSystemId($value)
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: PermissionRepository::class)]
final class Permission extends ServiceModel
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'Permissions';

    /**
     * @var string
     */
    protected $primaryKey = 'permissionId';

    /**
     * @var string
     */
    protected string $map = 'permission';

    /**
     * @var array
     */
    protected $fillable = [
        'systemId',
        'clientId',
        'permissionName',
        'permissionValue',
        'permissionDescription',
        'permissionActive',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'permissionActive' => 'bool',
        'createdAt' => 'datetime',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RolePermission::class, 'permissionId', 'roleId');
    }
}
