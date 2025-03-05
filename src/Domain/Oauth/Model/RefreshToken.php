<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Model;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Passport\RefreshToken as PassportRefreshToken;

/**
 * 
 *
 * @property string $id
 * @property string $access_token_id
 * @property bool $revoked
 * @property Carbon|null $expires_at
 * @method static Builder|RefreshToken newModelQuery()
 * @method static Builder|RefreshToken newQuery()
 * @method static Builder|RefreshToken query()
 * @method static Builder|RefreshToken whereAccessTokenId($value)
 * @method static Builder|RefreshToken whereExpiresAt($value)
 * @method static Builder|RefreshToken whereId($value)
 * @method static Builder|RefreshToken whereRevoked($value)
 * @mixin Eloquent
 */
final class RefreshToken extends PassportRefreshToken
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
    protected $table = 'OauthRefreshTokens';
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
     * Get the access token that the refresh token belongs to.
     *
     * @return BelongsTo
     */
    #[\Override]
    public function accessToken()
    {
        return $this->belongsTo(Passport::tokenModel());
    }

    /**
     * Revoke the token instance.
     *
     * @return bool
     */
    #[\Override]
    public function revoke()
    {
        return $this->forceFill(['revoked' => true])->save();
    }

    /**
     * Determine if the token is a transient JWT token.
     *
     * @return bool
     */
    #[\Override]
    public function transient()
    {
        return false;
    }
}
