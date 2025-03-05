<?php

declare(strict_types=1);

namespace Infrastructure\Eloquent\Model;

use App\Domain\Oauth\Model\Token;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Repository\ClientDeviceRepository;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $clientDeviceId
 * @property int $clientId
 * @property string $clientType
 * @property string|null $device
 * @property string|null $ip
 * @property string|null $loggedAt
 * @property string|null $logoutAt
 * @property Carbon|null $createdAt
 * @property-read Model|Eloquent $client
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ClientDevice newModelQuery()
 * @method static Builder|ClientDevice newQuery()
 * @method static Builder|ClientDevice query()
 * @method static Builder|ClientDevice whereClientDeviceId($value)
 * @method static Builder|ClientDevice whereClientId($value)
 * @method static Builder|ClientDevice whereClientType($value)
 * @method static Builder|ClientDevice whereCreatedAt($value)
 * @method static Builder|ClientDevice whereDevice($value)
 * @method static Builder|ClientDevice whereIp($value)
 * @method static Builder|ClientDevice whereLoggedAt($value)
 * @method static Builder|ClientDevice whereLogoutAt($value)
 * @property-read Token|null $token
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: ClientDeviceRepository::class)]
final class ClientDevice extends ServiceModel
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'ClientDevices';

    /**
     * @var
     */
    protected $primaryKey = 'clientDeviceId';

    /**
     * @var string[]
     */
    protected $fillable = [
        'clientId',
        'tokenId',
        'clientType',
        'device',
        'ip',
        'loggedAt',
        'logoutAt',
    ];

    protected $casts = [
        'createdAt' => 'datetime',
        'loggedAt' => 'datetime',
        'logoutAt' => 'datetime',
    ];

    /**
     * @return MorphTo
     *
     * @link \App\Domain\UMAC\Model\Citizen
     * @link \App\Domain\CUM\Model\User
     */
    public function client(): MorphTo
    {
        return $this->morphTo('client', 'clientType', 'clientId');
    }

    /**
     * @return HasOne
     */
    public function token(): HasOne
    {
        return $this->hasOne(Token::class, 'deviceId', 'clientDeviceId');
    }
}
