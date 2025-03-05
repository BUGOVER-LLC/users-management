<?php

declare(strict_types=1);

namespace Infrastructure\Http\Macro;

abstract class AbstractMacro
{
    abstract protected function register(): void;

    final public static function bind(): void
    {
        app(static::class)->register();
    }
}
