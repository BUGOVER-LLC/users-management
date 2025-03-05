<?php

declare(strict_types=1);

namespace Infrastructure\Eloquent\Concerns;

trait HasAvatar
{
    /**
     * @return string|null
     */
    public function avatar(): ?string
    {
        return $this?->profile?->avatar['path'] ?? null;
    }
}
