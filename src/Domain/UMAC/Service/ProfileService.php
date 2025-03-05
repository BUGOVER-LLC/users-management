<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Service;

use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\ProfileRepository;

class ProfileService
{
    public function __construct(private readonly ProfileRepository $profileRepository)
    {
    }

    /**
     * @param int $psn
     * @return ?Profile
     */
    public function findProfileByPsn(int $psn): ?Profile
    {
        return $this->profileRepository->where('psn', '=', $psn)->findFirst();
    }

    public function findCitizenProfileByUuid(string $uuid): ?Profile
    {
        return $this->profileRepository->whereRelation('citizen', 'uuid', $uuid)->first();
    }

    public function findUserProfileByUuid(string $uuid): ?Profile
    {
        return $this->profileRepository->whereRelation('user', 'uuid', $uuid)->first();
    }
}
