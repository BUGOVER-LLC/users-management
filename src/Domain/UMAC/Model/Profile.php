<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Model;

use App\Core\Enum\Gender;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Repository\ProfileRepository;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $profileId
 * @property string|null $psn
 * @property string $firstName
 * @property string $lastName
 * @property string|null $patronymic
 * @property string|null $dateBirth
 * @property Gender|null $gender
 * @property array|null $avatar
 * @property string|null $currentLoginType
 * @property int|null $currentLoginId
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property \Illuminate\Support\Carbon $createdAt
 * @property \Illuminate\Support\Carbon $updatedAt
 * @property-read Citizen|null $citizen
 * @property-read mixed $full_name
 * @property-read \App\Domain\UMAC\Model\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCurrentLoginId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCurrentLoginType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDateBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $currentLogin
 * @mixin \Eloquent
 */
#[ModelEntity(repositoryClass: ProfileRepository::class)]
class Profile extends ServiceModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'Profiles';

    /**
     * @var string
     */
    protected $primaryKey = 'profileId';

    protected string $map = 'profile';

    /**
     * @var string[]
     */
    protected $fillable = [
        'psn',
        'firstName',
        'lastName',
        'patronymic',
        'dateBirth',
        'gender',
        'avatar',
        'currentLoginType',
        'currentLoginId',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'avatar' => 'array',
    ];

    /**
     * @return Attribute
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstName . ' ' . $this->lastName . ' ' . $this->patronymic,
        );
    }

    public function currentLogin(): MorphTo
    {
        return $this->morphTo(
            name: __FUNCTION__,
            type: 'currentLoginType',
            id: 'currentLoginId',
        );
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profileId', 'profileId');
    }

    /**
     * @return BelongsTo
     */
    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class, 'profileId', 'profileId');
    }
}
