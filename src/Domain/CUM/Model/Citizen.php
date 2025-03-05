<?php

declare(strict_types=1);

namespace App\Domain\CUM\Model;

use App\Core\Enum\AuthGuard;
use App\Core\Enum\DocumentType;
use App\Core\Enum\PersonType;
use App\Core\FileSystem\Casts\Attachments;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\Oauth\Model\Client;
use App\Domain\Oauth\Model\Token;
use App\Domain\UMAC\Model\Documents;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use Eloquent;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Concerns\HasAccounts;
use Infrastructure\Eloquent\Concerns\HasAvatar;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Eloquent\Model\ClientUserMapping;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\AuthenticateModel;
use Infrastructure\Illuminate\Model\Traits\Uuid;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\PersonalAccessTokenFactory;

/**
 *
 *
 * @property int $citizenId
 * @property string $uuid
 * @property int|null $userId
 * @property int|null $profileId
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $password
 * @property PersonType|null $personType
 * @property DocumentType|null $documentType
 * @property string|null $documentValue
 * @property array|null $documentFile
 * @property bool $isActive
 * @property bool $isChecked
 * @property string|null $lastActivityAt
 * @property string|null $lastDeactivateAt
 * @property Carbon|null $deletedAt
 * @property string $createdAt
 * @property string $updatedAt
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read ClientDevice|null $currentDevice
 * @property-read Profile|null $currentLogin
 * @property-read Collection<int, ClientDevice> $devices
 * @property-read int|null $devices_count
 * @property-read Collection<int, Documents> $documents
 * @property-read int|null $documents_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Profile|null $profile
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AuthenticateModel except(array $values = [])
 * @method static Builder|Citizen newModelQuery()
 * @method static Builder|Citizen newQuery()
 * @method static Builder|Citizen onlyTrashed()
 * @method static Builder|Citizen query()
 * @method static Builder|Citizen whereCitizenId($value)
 * @method static Builder|Citizen whereCreatedAt($value)
 * @method static Builder|Citizen whereDeletedAt($value)
 * @method static Builder|Citizen whereDocumentFile($value)
 * @method static Builder|Citizen whereDocumentType($value)
 * @method static Builder|Citizen whereDocumentValue($value)
 * @method static Builder|Citizen whereEmail($value)
 * @method static Builder|Citizen whereIsActive($value)
 * @method static Builder|Citizen whereIsChecked($value)
 * @method static Builder|Citizen whereLastActivityAt($value)
 * @method static Builder|Citizen whereLastDeactivateAt($value)
 * @method static Builder|Citizen wherePassword($value)
 * @method static Builder|Citizen wherePersonType($value)
 * @method static Builder|Citizen wherePhone($value)
 * @method static Builder|Citizen whereProfileId($value)
 * @method static Builder|Citizen whereUpdatedAt($value)
 * @method static Builder|Citizen whereUserId($value)
 * @method static Builder|Citizen whereUuid($value)
 * @method static Builder|Citizen withTrashed()
 * @method static Builder|Citizen withoutTrashed()
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: CitizenRepository::class)]
final class Citizen extends AuthenticateModel
{
    use HasAccounts;
    use HasApiTokens;
    use HasAvatar;
    use HasFactory;
    use SoftDeletes;
    use Uuid;

    //TODO add migration update_at column
    public $timestamps = false;

    public $id = null;
    public string $guard = AuthGuard::apiCitizens->value;
    /**
     * @var string
     */
    protected $table = 'Citizens';
    /**
     * @var string
     */
    protected $primaryKey = 'citizenId';
    protected string $map = 'citizen';
    /**
     * @var string[]
     */
    protected $fillable = [
        'userId',
        'profileId',
        'email',
        'phone',
        'password',
        'isActive',
        'isChecked',
        'uuid',
        'personType',
        'lastActivityAt',
        'lastDeactivateAt',
        'documentType',
        'documentValue',
        'documentFile',
    ];

    protected $casts = [
        'image' => 'array',
        'isActive' => 'bool',
        'isChecked' => 'bool',
        'personType' => PersonType::class,
        'documentType' => DocumentType::class,
        'documentFile' => Attachments::class,
    ];

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            related: User::class,
            through: Profile::class,
            firstKey: 'profileId',
            secondKey: 'profileId',
            localKey: 'citizenId',
            secondLocalKey: 'profileId',
        );
    }

    /**
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profileId', 'profileId');
    }

    /**
* 124
     *
     * @return MorphMany
     */
    public function mapping(): MorphMany
    {
        return $this->morphMany(ClientUserMapping::getTableName(), 'user', 'userType', 'userId');
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Documents::class, 'citizenId', 'citizenId');
    }

    /**
     * @return MorphMany
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(ClientDevice::class, 'client', 'clientType', 'clientId');
    }

    /**
     * @return MorphOne
     */
    public function currentDevice(): MorphOne
    {
        return $this
            ->morphOne(ClientDevice::class, 'client', 'clientType', 'clientId')
            ->whereNotNull('loggedAt')
            ->latest('loggedAt');
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'uuid';
    }

    public function createToken($name, array $scopes = [])
    {
        return Container::getInstance()->make(PersonalAccessTokenFactory::class)->make(
            userId: $this->{$this->getAuthIdentifierName()},
            name: $name,
            scopes: $scopes
        );
    }
}
