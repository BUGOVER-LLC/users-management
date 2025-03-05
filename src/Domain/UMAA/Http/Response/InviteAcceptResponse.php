<?php

declare(strict_types=1);

namespace App\Domain\UMAA\Http\Response;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use Illuminate\Http\Request;

class InviteAcceptResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
    }
}
