<?php

declare(strict_types=1);

namespace App\Domain\UMRA\Model;

use App\Domain\UMRA\Repository\RoomRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 *
 *
 * @property int $roomId
 * @property int|null $systemId
 * @property int|null $attributeId
 * @property string|null $roomName
 * @property string|null $roomValue
 * @property Carbon|null $createdAt
 * @method static Builder|Room except(array $values = [])
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereAttributeId($value)
 * @method static Builder|Room whereCreatedAt($value)
 * @method static Builder|Room whereRoomId($value)
 * @method static Builder|Room whereRoomName($value)
 * @method static Builder|Room whereRoomValue($value)
 * @method static Builder|Room whereSystemId($value)
 * @property string|null $roomDescription
 * @property Carbon $updatedAt
 * @property-read \App\Domain\UMRA\Model\Attribute $attribute
 * @method static Builder|Room whereRoomDescription($value)
 * @method static Builder|Room whereUpdatedAt($value)
 * @property bool|null $roomActive
 * @method static Builder|Room whereRoomActive($value)
 * @mixin Eloquent
 */
#[ModelEntity(RoomRepository::class)]
final class Room extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'Rooms';

    /**
     * @var string
     */
    protected $primaryKey = 'roomId';

    /**
     * @var string
     */
    protected string $map = 'room';

    /**
     * @var array
     */
    protected $fillable = [
        'roomName',
        'roomValue',
        'roomDescription',
        'systemId',
        'attributeId',
        'roomActive',
    ];

    protected $casts = [
        'roomActive' => 'bool',
    ];

    /**
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attributeId', 'attributeId');
    }
}
