<?php

declare(strict_types=1);

namespace App\Domain\Producer\Model;

use App\Domain\Oauth\Model\Client;
use App\Domain\Oauth\Model\Token;
use App\Domain\System\Model\System;
use Database\Factories\ProducerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Infrastructure\Illuminate\Model\Entity\AuthenticateModel;

/**
 *
 *
 * @method static Builder|Producer newModelQuery()
 * @method static Builder|Producer newQuery()
 * @method static Builder|Producer query()
 * @property int $producerId
 * @property string $username
 * @property string $email
 * @property string $password
 * @property Carbon $createdAt
 * @method static Builder|Producer whereCreatedAt($value)
 * @method static Builder|Producer whereEmail($value)
 * @method static Builder|Producer wherePassword($value)
 * @method static Builder|Producer whereProducerId($value)
 * @method static Builder|Producer whereUsername($value)
 * @property string|null $verifiedAt
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static ProducerFactory factory($count = null, $state = [])
 * @method static Builder|Producer whereVerifiedAt($value)
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|AuthenticateModel except(array $values = [])
 * @property string|null $rememberToken
 * @method static Builder|Producer whereRememberToken($value)
 * @property-read Collection<int, System> $systems
 * @property-read int|null $systems_count
 * @property int|null $currentSystemId
 * @method static Builder|Producer whereCurrentSystemId($value)
 * @mixin Eloquent
 */
class Producer extends AuthenticateModel
{
    use HasFactory;
    use Notifiable;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $rememberTokenName = 'rememberToken';

    /**
     * @var string
     */
    protected $table = 'Producers';

    /**
     * @var string
     */
    protected $primaryKey = 'producerId';

    /**
     * @var array
     */
    protected $fillable = [
        'currentSystemId',
        'username',
        'email',
        'password',
        'verifiedAt',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password',
        'rememberToken',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'createdAt' => 'datetime',
    ];

    protected string $map = 'producer';

    /**
     * @return HasMany
     */
    public function systems(): HasMany
    {
        return $this->hasMany(System::class, 'systemId', 'producerId');
    }
}
