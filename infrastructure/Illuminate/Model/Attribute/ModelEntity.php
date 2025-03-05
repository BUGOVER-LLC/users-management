<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Model\Attribute;

use Attribute;

#[Attribute]
class ModelEntity
{
    public function __construct(
        public readonly string|object $repositoryClass = '',
        public readonly bool $readonly = true,
    ) {
    }
}
