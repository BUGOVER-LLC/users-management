<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;
use Infrastructure\Http\Resource\UserResource;

final class GetUsersByAttrbiuteRoleController extends Controller
{
    public function __invoke(string $attributeValue, string $roleValue, QueryService $queryService)
    {
        $users = $queryService->getUsersByRoleInAttribute($attributeValue, $roleValue);

        return UserResource::collection($users);
    }
}
