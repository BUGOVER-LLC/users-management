<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Model\Entity;

use AllowDynamicProperties;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Contract\EntityContract;
use Infrastructure\Illuminate\Model\Traits\ScopeHelpers;

/**
 * Class ServiceModel
 *
 * @package ServiceEntityStory\Messenger
 * @method static first($attributes = ['*'])
 */
#[AllowDynamicProperties]
#[ModelEntity()]
class ServiceModel extends Model implements EntityContract
{
    use ScopeHelpers;

    /* @noinspection PhpUnused */
    public const string UPDATED_AT = 'updatedAt';

    /* @noinspection PhpUnused */
    public const string CREATED_AT = 'createdAt';

    /* @noinspection PhpUnused */
    public const string DELETED_AT = 'deletedAt';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';
}
