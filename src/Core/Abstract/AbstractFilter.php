<?php

declare(strict_types=1);

namespace App\Core\Abstract;

/**
 * Class BaseFilter
 *
 * @package App\Filters
 */
abstract class AbstractFilter
{
    /**
     * @var
     */
    protected $app;

    /**
     * BaseFilter constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->filter();
    }

    /**
     * @return mixed
     */
    abstract public function filter();
}
