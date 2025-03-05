<?php

declare(strict_types=1);

namespace App\Domain\System\Model;

use App\Core\FileSystem\Casts\Attachments;
use App\Domain\Oauth\Model\Client;
use App\Domain\Producer\Model\Producer;
use App\Domain\System\Repository\SystemRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Model\ClientUserMapping;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;
use Infrastructure\Illuminate\Model\Traits\Uuid;
use Laravel\Passport\Passport;

/**
 *
 *
 * @property int $systemId
 * @property int|null $producerId
 * @property string $systemName
 * @property Carbon|null $createdAt
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|System newModelQuery()
 * @method static Builder|System newQuery()
 * @method static Builder|System query()
 * @method static Builder|System whereCreatedAt($value)
 * @method static Builder|System whereProducerId($value)
 * @method static Builder|System whereSystemId($value)
 * @method static Builder|System whereSystemName($value)
 * @property string|null $systemLogo
 * @property-read Producer|null $producer
 * @method static Builder|System whereSystemLogo($value)
 * @property string|null $systemDomain
 * @property Carbon|null $updatedAt
 * @method static Builder|System whereSystemDomain($value)
 * @method static Builder|System whereUpdatedAt($value)
 * @property-read Client|null $publicClient
 * @property string|null $systemWebhookUrl
 * @method static Builder|System whereSystemWebhookUrl($value)
 * @property-read Collection<int, ClientUserMapping> $mapping
 * @property-read int|null $mapping_count
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: SystemRepository::class)]
class System extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'Systems';

    /**
     * @var string
     */
    protected $primaryKey = 'systemId';

    /**
     * @var string
     */
    protected string $map = 'system';

    /**
     * @var string[]
     */
    protected $fillable = [
        'producerId',
        'systemName',
        'systemLogo',
        'systemDomain',
    ];

    protected $casts = [
        'systemLogo' => Attachments::class,
    ];

    /**
     * Get all user's registered OAuth clients for a current system.
     *
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Passport::clientModel(), 'user_id', 'systemId');
    }

    /**
     * Get all user's registered OAuth clients for a current system.
     *
     * @return HasOne
     */
    public function publicClient(): HasOne
    {
        return $this
            ->hasOne(Passport::clientModel(), 'user_id', 'systemId')
            ->where('OauthClients.personal_access_client', '=', false)
            ->where('OauthClients.password_client', '=', false)
            ->where('OauthClients.revoked', '=', false);
    }

    /**
     * @return BelongsTo
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class, 'producerId', 'producerId');
    }

    /**
     * @return HasMany
     */
    public function mapping(): HasMany
    {
        return $this->hasMany(ClientUserMapping::class, 'systemId', 'systemId');
    }
}
