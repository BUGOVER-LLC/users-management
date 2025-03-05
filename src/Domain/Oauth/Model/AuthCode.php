<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Model;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Passport\AuthCode as PassportCodeAlias;
use Laravel\Passport\Passport;

/**
 * 
 *
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string|null $scopes
 * @property bool $revoked
 * @property Carbon|null $expires_at
 * @property-read Client|null $client
 * @method static Builder|AuthCode newModelQuery()
 * @method static Builder|AuthCode newQuery()
 * @method static Builder|AuthCode query()
 * @method static Builder|AuthCode whereClientId($value)
 * @method static Builder|AuthCode whereExpiresAt($value)
 * @method static Builder|AuthCode whereId($value)
 * @method static Builder|AuthCode whereRevoked($value)
 * @method static Builder|AuthCode whereScopes($value)
 * @method static Builder|AuthCode whereUserId($value)
 * @mixin Eloquent
 */
final class AuthCode extends PassportCodeAlias
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'OauthAuthCodes';
    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

   // public const CREATED_AT = 'createdAt';
   // public const UPDATED_AT = 'updatedAt';
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'revoked' => 'bool',
        'expires_at' => 'datetime',
    ];
    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Get the client that owns the authentication code.
     *
     * @return BelongsTo
     */
    #[\Override]
    public function client()
    {
        return $this->belongsTo(Passport::clientModel());
    }
}
