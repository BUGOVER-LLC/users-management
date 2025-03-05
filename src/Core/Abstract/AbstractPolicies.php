<?php

declare(strict_types=1);

namespace App\Core\Abstract;

use Illuminate\Auth\Access\HandlesAuthorization;

/**
 *
 */
abstract class AbstractPolicies
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function view($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function create($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function update($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    abstract public function delete($user, $model);

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    public function destroy($user, $model)
    {
        // @TODO
    }

    /**
     * @param $user
     * @param $model
     * @return mixed
     */
    public function viewAny($user, $model)
    {
        // @TODO
    }
}
