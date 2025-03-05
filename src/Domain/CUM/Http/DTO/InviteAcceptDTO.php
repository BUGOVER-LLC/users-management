<?php

namespace App\Domain\CUM\Http\DTO;

use App\Core\Abstract\AbstractDTO;

class InviteAcceptDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $token,
        public readonly string $email
    ){
    }

}
