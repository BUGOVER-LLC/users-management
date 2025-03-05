<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Controller;

use App\Domain\Micro\Service\QueryService;
use Infrastructure\Http\Controllers\Controller;

final class GetUserAttributeValueController extends Controller
{
    public function __invoke(string $uuid, QueryService $queryService)
    {
        $attributeValue = $queryService->getUserAttributeValue($uuid);

        return jsponse(['attributeValue' => $attributeValue]);
    }
}
