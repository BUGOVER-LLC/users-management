<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Repository;

use App\Core\Abstract\AbstractDTO;
use App\Domain\UMAC\Enum\PersonType;
use App\Domain\UMAC\Http\DTO\UserPagerDTO;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Model\User;
use App\Domain\UMRA\Model\Attribute;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Service\Repository\Contracts\WhereClauseContract;
use Service\Repository\Repositories\EloquentRepository;

class UserRepository extends EloquentRepository
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this
            ->setContainer($container)
            ->setModel(User::class)
            ->setRepositoryId('Users')
            ->setCacheDriver('redis');
    }

    /**
     * @param string $email
     * @param array $columns
     * @return ?User<Profile>
     */
    public function findByEmail(string $email, array $columns = ['*']): ?User
    {
        return $this
            ->where('email', '=', $email)
            ->findFirst($columns);
    }

    /**
     * @param string $uuid
     * @return User|null
     */
    public function findByUuid(string $uuid): ?User
    {
        return $this
            ->with('profile')
            ->where('uuid', $uuid)
            ->findFirst();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmailWithProfile(string $email): ?User
    {
        return $this
            ->where('email', '=', $email)
            ->with('profile', 'role')
            ->findFirst();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findUserByEmailWithInvitation(string $email): ?User
    {
        return $this
            ->where('email', '=', $email)
            ->with('invitation', 'role')
            ->findFirst();
    }

    /**
     * @param UserPagerDTO $dto
     * @return WhereClauseContract
     */
    public function getUserPagerBuilder(AbstractDTO $dto): WhereClauseContract
    {
        return $this
            ->when((PersonType::user->value === $dto->personType), function (Builder $qb) use ($dto) {
                return $qb
                    ->has('profile')
                    ->where('active', '=', $dto->active)
                    ->with('profile')
                    ->when($dto->search ?? false, fn(Builder $qb) => $qb
                        ->whereHas('profile', fn(Builder $qb) => $qb
                            ->where('firstName', 'LIKE', "%$dto->search%")
                            ->orWhere('lastName', 'LIKE', "%$dto->search%")
                            ->orWhere('psn', 'LIKE', "%$dto->search%")));
            })
            ->when((PersonType::invitation->value === $dto->personType), function (Builder $qb) use ($dto) {
                return $qb
                    ->has('invitation')
                    ->with(['invitation', 'profile'])
                    ->when($dto->search ?? false, fn(Builder $ab) => $qb
                        ->whereHas('invitation', fn(Builder $qb) => $qb
                            ->where('psnInfo->firstName', 'LIKE', "%$dto->search%")
                            ->orWhere('psnInfo->lastName', 'LIKE', "%$dto->search%")
                            ->orWhere('psnInfo->psn', 'LIKE', "%$dto->search%")));
            });
    }

    /**
     * @param int $parentId
     * @return ?Attribute
     */
    public function findParentAttribute(int $parentId): ?Attribute
    {
        return $this
            ->where('userId', '=', $parentId)
            ->with('attribute.resource')
            ->findFirst()
            ->attribute;
    }

    /**
     * @param int $roleId
     * @return Collection
     */
    public function findAllByRoleId(int $roleId): Collection
    {
        return $this->where('roleId', '=', $roleId)->findAll();
    }

    /**
     * @param $judgeUuid
     * @return Collection<User>
     */
    public function findALlUserChildsByParentUuid($judgeUuid): Collection
    {
        return $this
            ->where('parentId', '!=')
            ->whereHas('parent', fn(Builder $qb) => $qb->where('uuid', '=', $judgeUuid))
            ->with('role')
            ->findAll();
    }
}
