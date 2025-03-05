<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Model\Entity;

use AllowDynamicProperties;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Infrastructure\Illuminate\Model\Attribute\ModelEntity;
use Infrastructure\Illuminate\Model\Contract\EntityContract;
use Infrastructure\Illuminate\Model\Traits\ScopeHelpers;
use Laravel\Passport\HasApiTokens;

/**
 * Class ServiceAuthenticate
 *
 * @package Service\Messenger
 */
#[AllowDynamicProperties]
#[ModelEntity()]
class AuthenticateModel extends Authenticate implements EntityContract
{
    use ScopeHelpers;
    use HasApiTokens;
    use Notifiable;

    /* @noinspection PhpUnused */
    public const string UPDATED_AT = 'updatedAt';

    /* @noinspection PhpUnused */
    public const string CREATED_AT = 'createdAt';

    /* @noinspection PhpUnused */
    public const string DELETED_AT = 'deletedAt';

    /**
     * @var string[]
     * @noinspection PhpUnused
     */
    public array $socketAuth = [];

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Specifies the user's FCM tokens
     *
     * @return string|null
     * @noinspection PhpUnused
     */
    public function routeNotificationForFcm(): ?string
    {
        return $this->fcm()->first()->key ?? null;
    }
}
