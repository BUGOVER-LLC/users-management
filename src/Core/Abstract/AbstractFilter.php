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
     * BaseFilter constructor.
     *
     * @param $app
     */
    public function __construct(/**
     * @var
     */
    protected $app)
    {
        $this->filter();
    }

    /**
     * @return mixed
     */
    abstract public function filter();
}
