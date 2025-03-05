<?php

declare(strict_types=1);

namespace App\Domain\Oauth\Claim;

use Laravel\Passport\Bridge\AccessToken;

class CustomAccessToken extends AccessToken
{
    use CustomClaim;
}
