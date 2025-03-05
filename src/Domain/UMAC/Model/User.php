<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Model;

use App\Core\Enum\AuthGuard;
use App\Domain\CUM\Model\Citizen;
use App\Domain\Oauth\Model\Client;
use App\Domain\Oauth\Model\Token;
use App\Domain\UMAC\Repository\UserRepository;
use App\Domain\UMRA\Model\Attribute;
use App\Domain\UMRP\Model\Role;
use Database\Factories\Domain\UMAC\Model\UserFactory;
use Eloquent;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Eloquent\Concerns\HasAccounts;
use Infrastructure\Eloquent\Concerns\HasAvatar;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Eloquent\Model\ClientUserMapping;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\AuthenticateModel;
use Infrastructure\Illuminate\Model\Traits\Uuid;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\PersonalAccessTokenFactory;
use Laravel\Passport\PersonalAccessTokenResult;

/**
 *
 *
 * @property int $userId
 * @property string $uuid
 * @property int|null $profileId
 * @property int $roleId
 * @property int|null $attributeId
 * @property string $email
 * @property string|null $phone
 * @property string|null $password
 * @property bool $active
 * @property Carbon|null $deletedAt
 * @property Carbon $updatedAt
 * @property Carbon $createdAt
 * @property-read Attribute|null $attribute
 * @property-read Citizen|null $citizen
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read Collection<int, InvitationUser> $deletedInvitations
 * @property-read int|null $deleted_invitations_count
 * @property-read InvitationUser|null $invitation
 * @property-read Collection<int, InvitationUser> $invitations
 * @property-read int|null $invitations_count
 * @property-read Collection<int, ClientUserMapping> $mapping
 * @property-read int|null $mapping_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Profile|null $profile
 * @property-read Role $role
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection<int, InvitationUser> $trashedInvitations
 * @property-read int|null $trashed_invitations_count
 * @method static Builder|AuthenticateModel except(array $values = [])
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static Builder|User whereActive($value)
 * @method static Builder|User whereAttributeId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereProfileId($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUserId($value)
 * @method static Builder|User whereUuid($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 * @property int|null $parentId
 * @property-read Profile|null $currentLogin
 * @method static Builder|User whereParentId($value)
 * @property-read Collection<int, ClientDevice> $devices
 * @property-read int|null $devices_count
 * @property-read ClientDevice|null $currentDevice
 * @property-read User|null $parent
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: UserRepository::class)]
final class User extends AuthenticateModel
{
    use HasAccounts;
    use HasApiTokens;
    use HasAvatar;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Uuid;

    /**
     * @var string
     */
    public string $guard = AuthGuard::apiUsers->value;

    /**
     * @var string
     */
    protected $table = 'Users';

    /**
     * @var string
     */
    protected $primaryKey = 'userId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'profileId',
        'parentId',
        'email',
        'phone',
        'roleId',
        'attributeId',
        'password',
        'active',
    ];

    /**
     * @var string
     */
    protected string $map = 'user';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'rememberToken',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'emailVerifiedAt' => 'datetime',
        'active' => 'bool',
    ];

    /**
     * @return HasMany
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(InvitationUser::class, 'userId', 'userId');
    }

    /**
     * @return HasMany
     */
    public function trashedInvitations(): HasMany
    {
        return $this->hasMany(InvitationUser::class, 'userId', 'userId')->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function deletedInvitations(): HasMany
    {
        return $this->hasMany(InvitationUser::class, 'userId', 'userId')->withTrashed();
    }

    /**
     * @return HasOne
     */
    public function invitation(): HasOne
    {
        return $this->hasOne(InvitationUser::class, 'userId', 'userId')->latest();
    }

    /**
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profileId', 'profileId');
    }

    /**
     * @return HasOne
     */
    public function citizen(): HasOne
    {
        return $this->hasOne(Citizen::class, 'userId', 'userId');
    }

    /**
     * @param string $email
     * @return static
     */
    public function findForPassport(string $email): static
    {
        return static::where('email', '=', $email)->first();
    }

    /**
     * @return MorphMany
     */
    public function mapping(): MorphMany
    {
        return $this->morphMany(ClientUserMapping::class, 'user', 'userType', 'userId');
    }

    /**
     * @param $password
     * @return bool
     * @noinspection PhpUnused
     */
    public function validateForPassportPasswordGrant($password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roleId', 'roleId');
    }

    /**
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attributeId', 'attributeId');
    }

    /**
     * @return MorphMany
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(ClientDevice::class, 'client', 'clientType', 'clientId');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parentId', 'userId');
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

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $scopes
     * @return PersonalAccessTokenResult
     * @throws BindingResolutionException
     */
    public function createToken($name, array $scopes = []): PersonalAccessTokenResult
    {
        return Container::getInstance()->make(PersonalAccessTokenFactory::class)->make(
            userId: $this->{$this->getAuthIdentifierName()},
            name: $name,
            scopes: $scopes
        );
    }
}
