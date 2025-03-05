<?php

namespace App\Domain\CUM\Service;

use App\Domain\CUM\Model\ResetCodePasswords;
use App\Domain\CUM\Repository\ResetCodePasswordsRepository;


class ResetCodePasswordsService
{
    public function __construct(private readonly ResetCodePasswordsRepository $resetCodePasswordsRepository)
    {
    }

    /**
     * @param $email
     * @return bool|null
     * @throws \Service\Repository\Exceptions\RepositoryException
     */
    public function resetCodeDelete($email)
    {

        return $this->resetCodePasswordsRepository->where(['email' => $email])->deletes();
    }

    /**
     * @param $email
     * @param $code
     * @return \Illuminate\Database\Eloquent\Model|object|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Service\Repository\Exceptions\RepositoryException
     */
    public function createResetCodePassword($email, $code): ?ResetCodePasswords
    {
        return $this->resetCodePasswordsRepository->create([
            'email' => $email,
            'code' => $code,
        ]);

    }

    /**
     * @param $code
     * @return false|mixed|object|null
     */
    public function checkResetCodePassword($code){
        $passwordReset = $this->resetCodePasswordsRepository->firstWhere(['code', $code]);
        if ($passwordReset->createdAt > now()->addMinutes(5)) {
            $passwordReset->delete();
            return false;
        }
        return $passwordReset;
    }


}
