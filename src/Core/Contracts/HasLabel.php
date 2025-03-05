<?php

declare(strict_types=1);

namespace App\Core\Contracts;

interface HasLabel
{
    public function getLabel(): ?string;
}
