<?php

declare(strict_types=1);

namespace Infrastructure\Eloquent\Concerns;

use App\Domain\UMAC\Model\Profile;

trait HasAccounts
{
    public function currentLogin()
    {
        return $this->morphOne(
            Profile::class,
            'currentLogin',
            type: 'currentLoginType',
            id: 'currentLoginId',
        );
    }
}
