<?php

declare(strict_types=1);

namespace Infrastructure\Eloquent\Model;

use App\Domain\System\Model\System;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Repository\ClientUserMappingRepository;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 *
 *
 * @property int $systemId
 * @property int $userId
 * @property string $userType
 * @property Carbon $createdAt
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ClientUserMapping newModelQuery()
 * @method static Builder|ClientUserMapping newQuery()
 * @method static Builder|ClientUserMapping query()
 * @method static Builder|ClientUserMapping whereCreatedAt($value)
 * @method static Builder|ClientUserMapping whereSystemId($value)
 * @method static Builder|ClientUserMapping whereUserId($value)
 * @method static Builder|ClientUserMapping whereUserType($value)
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $client
 * @property-read System $system
 * @property int $clientUserMappingId
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static Builder|ClientUserMapping whereClientUserMappingId($value)
 * @property Carbon|null $deletedAt
 * @method static Builder|ClientUserMapping onlyTrashed()
 * @method static Builder|ClientUserMapping whereDeletedAt($value)
 * @method static Builder|ClientUserMapping withTrashed()
 * @method static Builder|ClientUserMapping withoutTrashed()
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: ClientUserMappingRepository::class)]
final class ClientUserMapping extends ServiceModel
{
    use SoftDeletes;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'ClientUserMapping';

    /**
     * @var
     */
    protected $primaryKey = 'clientUserMappingId';

    /**
     * @var string[]
     */
    protected $fillable = [
        'systemId',
        'userId',
        'userType',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'createdAt' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'systemId', 'systemId');
    }

    /**
     * @return BelongsTo
     *
     * @link \App\Domain\UMAC\Model\User
     * @link \App\Domain\CUM\Model\Citizen
     */
    public function user(): BelongsTo
    {
        return $this->morphTo('user', 'userType', 'userId');
    }
}
