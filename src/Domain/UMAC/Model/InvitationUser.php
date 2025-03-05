<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Model;

use App\Domain\UMAC\Repository\InvitationUserRepository;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property-read User|null $user
 * @method static Builder|InvitationUser newModelQuery()
 * @method static Builder|InvitationUser newQuery()
 * @method static Builder|InvitationUser query()
 * @property int $invitationUserId
 * @property int $userId
 * @property string $inviteUrl
 * @property \Illuminate\Support\Carbon $passed
 * @property \Illuminate\Support\Carbon $createdAt
 * @method static Builder|InvitationUser whereCreatedAt($value)
 * @method static Builder|InvitationUser whereInvitationUserId($value)
 * @method static Builder|InvitationUser whereInviteUrl($value)
 * @method static Builder|InvitationUser wherePassed($value)
 * @method static Builder|InvitationUser whereUserId($value)
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property array{psn: string, gender: string, firstName: string, lastName:string, dateBirth: string, patronymic: string} $psnInfo
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static Builder|InvitationUser onlyTrashed()
 * @method static Builder|InvitationUser whereDeletedAt($value)
 * @method static Builder|InvitationUser withTrashed()
 * @method static Builder|InvitationUser withoutTrashed()
 * @property string $inviteToken
 * @method static Builder|InvitationUser whereInviteToken($value)
 * @method static Builder|InvitationUser wherePsnInfo($value)
 * @property string|null $inviteEmail
 * @method static Builder|InvitationUser whereInviteEmail($value)
 * @property bool $accept
 * @method static Builder|InvitationUser whereAccept($value)
 * @property array|null $psnInfo
 * @property \Illuminate\Support\Carbon|null $acceptedAt
 * @method static Builder|InvitationUser whereAcceptedAt($value)
 * @mixin Eloquent
 */
#[ModelEntity(repositoryClass: InvitationUserRepository::class)]
final class InvitationUser extends ServiceModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'InvitationUsers';

    /**
     * @var string
     */
    protected $primaryKey = 'invitationUserId';

    /**
     * @var string
     */
    protected string $map = 'invitationUser';

    /**
     * @var string[]
     */
    protected $fillable = [
        'userId',
        'inviteUrl',
        'inviteToken',
        'inviteEmail',
        'passed',
        'psnInfo',
        'acceptedAt',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'passed' => 'datetime',
        'createdAt' => 'datetime',
        'psnInfo' => 'json',
        'acceptedAt' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }
}
