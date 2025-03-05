<?php

declare(strict_types=1);

namespace App\Domain\Producer\Service;

use App\Domain\Producer\Model\Producer;
use App\Domain\Producer\Repository\ProfileRepository;
use App\Domain\UMAC\Model\Profile;

class ProfileService
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    /**
     * @param int $profileId
     * @return Producer|null
     */
    public function getProfileById(int $profileId): ?Profile
    {
        return $this->profileRepository->find($profileId);
    }
}
