<?php

namespace App\Domain\CUM\Model;

use App\Domain\CUM\Repository\InvitationCitizenRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

#[ModelEntity(repositoryClass: InvitationCitizenRepository::class)]
/**
 * 
 *
 * @property int $invitationCitizenId
 * @property int|null $citizenId
 * @property string $inviteUrl
 * @property string $inviteEmail
 * @property string $inviteToken
 * @property \Illuminate\Support\Carbon $passed
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property \Illuminate\Support\Carbon $createdAt
 * @property-read \App\Domain\CUM\Model\Citizen|null $citizen
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereCitizenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereInvitationCitizenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereInviteEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereInviteToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen whereInviteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen wherePassed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InvitationCitizen withoutTrashed()
 * @mixin \Eloquent
 */
class InvitationCitizen extends ServiceModel
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
    protected $table = 'InvitationCitizens';

    /**
     * @var string
     */
    protected $primaryKey = 'invitationCitizenId';

    protected string $map = 'invitationCitizen';

    /**
     * @var string[]
     */
    protected $fillable = [
        'citizenId',
        'inviteUrl',
        'inviteToken',
        'inviteEmail',
        'passed',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'passed' => 'datetime',
        'createdAt' => 'datetime',
    ];

    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'citizenId', 'citizenId');
    }
}
