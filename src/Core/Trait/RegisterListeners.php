<?php

declare(strict_types=1);

namespace App\Core\Trait;

use Illuminate\Support\Facades\Event;

use function is_array;

/**
 * @property-read array $listeners
 */
trait RegisterListeners
{
    protected function registerListeners(): void
    {
        foreach ($this->listeners as $event => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    Event::listen($event, $listener);
                }
            } else {
                Event::listen($event, $listeners);
            }
        }
    }
}
