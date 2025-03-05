<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Model;

use App\Core\Enum\OauthClientType;
use App\Domain\System\Model\System;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Passport\Client as PassportCLient;
use Laravel\Passport\Database\Factories\ClientFactory;
use Laravel\Passport\ResolvesInheritedScopes;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $secret
 * @property string|null $provider
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property OauthClientType clientType
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, AuthCode> $authCodes
 * @property-read int|null $auth_codes_count
 * @property-read string|null $plain_secret
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @method static ClientFactory factory($count = null, $state = [])
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static Builder|Client query()
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client wherePasswordClient($value)
 * @method static Builder|Client wherePersonalAccessClient($value)
 * @method static Builder|Client whereProvider($value)
 * @method static Builder|Client whereRedirect($value)
 * @method static Builder|Client whereRevoked($value)
 * @method static Builder|Client whereSecret($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUserId($value)
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property-read System|null $system
 * @property-read mixed $client_type
 * @mixin Eloquent
 */
final class Client extends PassportCLient
{
//    use HasFactory;
    use ResolvesInheritedScopes;

    public const string CREATED_AT = 'createdAt';

    public const string UPDATED_AT = 'updatedAt';

    /**
     * The temporary plain-text client secret.
     *
     * This is only available during the request that created the client.
     *
     * @var string|null
     */
    public $plainSecret;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'OauthClients';
    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];
    /* @noinspection PhpUnused */
    // public const string CREATED_AT = 'createdAt';

    /* @noinspection PhpUnused */
    //  public const string DELETED_AT = 'deletedAt';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'secret',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'grant_types' => 'array',
        'scopes' => 'array',
        'personal_access_client' => 'bool',
        'password_client' => 'bool',
        'revoked' => 'bool',
        'createdAt' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class, 'user_id', 'systemId');
    }

    /**
     * @return Attribute
     */
    public function clientType(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->password_client) {
                    return OauthClientType::password;
                }
                if ($this->personal_access_client) {
                    return OauthClientType::personal;
                }

                return OauthClientType::public;
            },
        );
    }
}
