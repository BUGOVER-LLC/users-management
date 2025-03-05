<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Model;

use App\Domain\UMAC\Model\User;
use App\Domain\UMRA\Model\Attribute;
use App\Domain\UMRP\Repository\RoleRepository;
use Database\Factories\Domain\UMRL\Model\RoleFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 *
 *
 * @property Role $role
 * @property string $roleName
 * @property string $roleDescription
 * @property bool $roleActive
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static RoleFactory factory($count = null, $state = [])
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereRoleDescription($value)
 * @method static Builder|Role whereRoleId($value)
 * @method static Builder|Role whereRoleName($value)
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static Builder|Role whereRoleActive($value)
 * @property Carbon|null $createdAt
 * @method static Builder|Role whereCreatedAt($value)
 * @property-read Collection<int, Attribute> $attributes
 * @property-read int|null $attributes_count
 * @method static Builder|ServiceModel except(array $values = [])
 * @property int $roleId
 * @property string|null $roleValue
 * @method static Builder|Role whereRoleValue($value)
 * @property int|null $systemId
 * @property int|null $clientId
 * @method static Builder|Role whereClientId($value)
 * @method static Builder|Role whereSystemId($value)
 * @property bool|null $hasSubordinates
 * @property RolePermission|null $pivot
 * @method static Builder|Role whereHasSubordinates($value)
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: RoleRepository::class)]
final class Role extends ServiceModel
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'Roles';

    /**
     * @var string
     */
    protected $primaryKey = 'roleId';

    /**
     * @var string[]
     */
    protected $fillable = [
        'systemId',
        'clientId',
        'roleName',
        'roleValue',
        'roleDescription',
        'roleActive',
        'hasSubordinates',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'roleActive' => 'bool',
        'hasSubordinates' => 'bool',
        'createdAt' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'roleId', 'roleId');
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Permission::class,
            table: RolePermission::class,
            foreignPivotKey: 'roleId',
            relatedPivotKey: 'permissionId'
        )->withPivot(['access']);
    }
}
