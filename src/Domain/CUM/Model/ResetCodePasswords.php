<?php

namespace App\Domain\CUM\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Infrastructure\Illuminate\Model\Entity\ServiceModel;

/**
 * 
 *
 * @property int $citizenResetPasswordId
 * @property string $email
 * @property string $code
 * @property \Illuminate\Support\Carbon $createdAt
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords whereCitizenResetPasswordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResetCodePasswords whereEmail($value)
 * @mixin \Eloquent
 */
class ResetCodePasswords extends ServiceModel
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'CitizenResetPasswords';
    /**
     * @var string
     */
    protected $primaryKey = 'citizenResetPasswordId';

    protected string $map = 'resetCodePassword';

    /**
     * @var string[]
     */
    protected $fillable = [
        'email',
        'code',
        'createdAt',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'createdAt' => 'datetime',
    ];
}
