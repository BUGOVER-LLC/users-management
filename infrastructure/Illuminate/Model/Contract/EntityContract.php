<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Model\Contract;

interface EntityContract
{
    public function getModelRepositoryClass(): string;
}
