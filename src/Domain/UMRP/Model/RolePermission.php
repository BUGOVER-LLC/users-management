<?php

declare(strict_types=1);

namespace App\Domain\UMRP\Model;

use App\Domain\UMRP\Repository\RolePermissionRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Cast\SetTypeCast;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $roleId
 * @property int $permissionId
 * @property Carbon $createdAt
 * @method static Builder|RolePermission newModelQuery()
 * @method static Builder|RolePermission newQuery()
 * @method static Builder|RolePermission query()
 * @method static Builder|RolePermission whereCreatedAt($value)
 * @method static Builder|RolePermission wherePermissionId($value)
 * @method static Builder|RolePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @property string|null $access
 * @method static Builder|RolePermission whereAccess($value)
 * @property int $rolePermissionId
 * @method static Builder|RolePermission whereRolePermissionId($value)
 * @mixin Eloquent
 */
#[ModelEntity(RolePermissionRepository::class)]
final class RolePermission extends ServiceModel
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'RolePermission';

    /**
     * @var string
     */
    protected $primaryKey = 'rolePermissionId';

    /**
     * @var array
     */
    protected $fillable = [
        'roleId',
        'permissionId',
        'access',
    ];

    protected $casts = [
        'createdAt' => 'datetime',
        'access' => SetTypeCast::class
    ];
}
