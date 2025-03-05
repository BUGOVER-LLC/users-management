<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\PathItem;
use OpenApi\Attributes\Response;

#[
    Info(
        version: '0.1',
        description: 'Auth microservice endpoints',
        title: 'API',
    )
]
#[
    PathItem(
        path: '/api/resource.json',
    ),
    Response(
        response: '200',
        description: 'An example resource',
    ),
    OA\SecurityScheme(
        securityScheme: 'bearerAuth',
        type: 'http',
        name: 'Authorization',
        in: 'header',
        scheme: 'bearer',
    )
]
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
