<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Model;

use App\Domain\Oauth\Repository\OauthAccessTokenRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Laravel\Passport\Passport;
use Laravel\Passport\ResolvesInheritedScopes;
use Laravel\Passport\Token as PassportToken;

use function array_key_exists;
use function in_array;

/**
 *
 *
 * @property string $id
 * @property int|null $user_id
 * @property int $client_id
 * @property string|null $name
 * @property array|null $scopes
 * @property bool $revoked
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $expires_at
 * @property-read Client|null $client
 * @method static Builder|Token newModelQuery()
 * @method static Builder|Token newQuery()
 * @method static Builder|Token query()
 * @method static Builder|Token whereClientId($value)
 * @method static Builder|Token whereCreatedAt($value)
 * @method static Builder|Token whereExpiresAt($value)
 * @method static Builder|Token whereId($value)
 * @method static Builder|Token whereName($value)
 * @method static Builder|Token whereRevoked($value)
 * @method static Builder|Token whereScopes($value)
 * @method static Builder|Token whereUpdatedAt($value)
 * @method static Builder|Token whereUserId($value)
 * @property-read RefreshToken|null $refreshToken
 * @property int|null $deviceId
 * @property-read ClientDevice|null $device
 * @method static Builder|Token whereDeviceId($value)
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: OauthAccessTokenRepository::class)]
final class Token extends PassportToken
{
    use ResolvesInheritedScopes;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'OauthAccessTokens';
    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'deviceId',
        'name',
        'scopes',
        'revoked',
        'expires_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'scopes' => 'array',
        'revoked' => 'bool',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the client that the token belongs to.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Passport::clientModel());
    }

    /**
     * Get the user that the token belongs to.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        $provider = config('auth.guards.api.provider');
        $model = config('auth.providers.' . $provider . '.model');

        return $this->belongsTo($model, 'user_id', (new $model())->getKeyName());
    }

    /**
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(ClientDevice::class, 'deviceId', 'clientDeviceId');
    }

    /**
     * Determine if the token is missing a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function cant($scope): bool
    {
        return !$this->can($scope);
    }

    /**
     * Determine if the token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function can($scope): bool
    {
        if (in_array('*', $this->scopes, true)) {
            return true;
        }

        $scopes = Passport::$withInheritedScopes
            ? $this->resolveInheritedScopes($scope)
            : [$scope];

        foreach ($scopes as $scop) {
            if (array_key_exists($scop, array_flip($this->scopes))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Revoke the token instance.
     *
     * @return bool
     */
    public function revoke(): bool
    {
        return $this->forceFill(['revoked' => true])->save();
    }

    /**
     * Determine if the token is a transient JWT token.
     *
     * @return bool
     */
    public function transient(): bool
    {
        return false;
    }
}
