<?php

declare(strict_types=1);

namespace App\Core\Abstract;

use App\Domain\CUM\Model\Citizen;
use App\Domain\Producer\Model\Producer;
use App\Domain\UMAC\Model\User;
use ReflectionClass;

abstract class AbstractDTO
{
    private ?object $user = null;

    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|Producer|Citizen|null
     */
    public function getUser(): ?object
    {
        return $this->user;
    }

    public function setUser(object $user): static
    {
        $this->user = $user;
        $this->setId($user->{$user->getKeyName()});

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $ref_class = new ReflectionClass(static::class);

        return $ref_class->getConstructor()->getParameters();
    }

    public function toArray(): array
    {
        return collect($this)
            ->except(['id', 'user'])
            ->toArray();
    }
}
