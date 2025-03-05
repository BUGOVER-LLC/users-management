<?php

declare(strict_types=1);

namespace Infrastructure\Http\Resource\Oauth;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Core\Trait\ConvertsSchemaToArray;
use Illuminate\Http\Request;
use Infrastructure\Http\Schemas\CreateClientSchema;

class CreateClientResource extends AbstractResource
{
    use ConvertsSchemaToArray;

    public function toSchema(Request $request): AbstractSchema
    {
        return new CreateClientSchema();
    }
}
