<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Model;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessClient as PassportPersonalAccessClient;

/**
 * 
 *
 * @property int $id
 * @property int $client_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client|null $client
 * @method static Builder|PersonalClient newModelQuery()
 * @method static Builder|PersonalClient newQuery()
 * @method static Builder|PersonalClient query()
 * @method static Builder|PersonalClient whereClientId($value)
 * @method static Builder|PersonalClient whereCreatedAt($value)
 * @method static Builder|PersonalClient whereId($value)
 * @method static Builder|PersonalClient whereUpdatedAt($value)
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @mixin Eloquent
 */
final class PersonalClient extends PassportPersonalAccessClient
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'OauthPersonalAccessClients';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    public const string CREATED_AT = 'createdAt';
    public const string UPDATED_AT = 'updatedAt';

    /**
     * Get all of the authentication codes for the client.
     *
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Passport::clientModel());
    }
}
