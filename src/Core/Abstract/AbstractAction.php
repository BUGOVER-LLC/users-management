<?php

declare(strict_types=1);

namespace App\Core\Abstract;

use Illuminate\Support\Facades\DB;

/**
 * @method run(...$arguments)
 */
abstract class AbstractAction
{
    /**
     * @param ...$arguments
     * @return mixed
     */
    public function transactionalRun(...$arguments): mixed
    {
        if (0 !== DB::connection(DB::getDefaultConnection())->transactionLevel()) {
            return static::run(...$arguments);
        }

        return DB::transaction(function () use ($arguments) {
            return static::run(...$arguments);
        });
    }
}
