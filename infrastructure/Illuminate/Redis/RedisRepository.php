<?php

declare(strict_types=1);

namespace Infrastructure\Illuminate\Redis;

use App\Core\Abstract\AbstractDTO;
use App\Core\Api\Ekeng\DTO\EkgDTO;
use App\Core\Enum\EmailType;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Enum\RedisKey;
use App\Domain\UMAC\Http\Schema\ProfileInfoSchema;
use Exception;
use Redis;
use RedisException;

class RedisRepository
{
    public function __construct(public readonly Redis $redis)
    {
        try {
            $host = config('database.redis.default.host');
            $port = (int)config('database.redis.default.port');
            $password = config('database.redis.default.password');

            $this->connect(host: $host, port: $port, password: $password);
        } catch (Exception $exception) {
            // Log the exception
            logger($exception);
        }
    }


    /**
     * @throws RedisException
     */
    public function connect(string $host, int $port, string $password = null): static
    {
        $this->redis->connect($host, $port);

        // If a password is provided, authenticate
        if ($password) {
            $this->redis->auth($password);
        }

        return $this;
    }

    /**
     * @throws RedisException
     */
    public function saveCitizenByPsn(EkgDTO $citizen): void
    {
        $key = RedisKey::citizenCheckPsn->value.$citizen->psn;
        $citizen = igbinary_serialize($citizen);

        $this->redis->hMSet(
            $key,
            [$citizen]
        );
        $this->redis->expire($key, 2592000);
    }

    /**
     * @param int|string $psn
     * @return false|mixed|null
     * @throws RedisException
     */
    public function findCitizenByPsn(int|string $psn): null|EkgDTO|Citizen
    {
        $key = RedisKey::citizenCheckPsn->value.$psn;
        $result = $this->redis->hMGet($key, ['0']);

        if (!$result || !$result[0]) {
            return null;
        }

        return igbinary_unserialize($result[0]);
    }

    /**
     * @param string $authenticator
     * @return array
     * @throws RedisException
     */
    public function producerGetSendCode(string $authenticator): array
    {
        $result = $this->redis->hMGet(
            EmailType::acceptCode->value.':'.$authenticator,
            ['auth', 'code', 'email']
        );

        return $result ? [
            'auth' => $result['auth'] ? igbinary_unserialize($result['auth']) : null,
            'code' => $result['code'] ? igbinary_unserialize($result['code']) : null,
            'email' => $result['email'] ? igbinary_unserialize($result['email']) : null,
        ] : [];
    }

    /**
     * @throws RedisException
     */
    public function setProducerAuthData(string $authenticator, string $accept_code, string $email): void
    {
        $this->redis->hMSet(
            EmailType::acceptCode->value.':'.$authenticator,
            [
                'code' => igbinary_serialize($accept_code),
                'auth' => igbinary_serialize($authenticator),
                'email' => igbinary_serialize($email),
            ]
        );
        $this->redis->expire(EmailType::acceptCode->value.':'.$authenticator, 1200);
    }

    /**
     * @param string $authenticator
     * @return void
     * @throws RedisException
     */
    public function deleteProducerPasswordConfirm(string $authenticator): void
    {
        $this->redis->hDel(
            EmailType::acceptCode->value.':'.$authenticator,
            'auth',
            'code',
            'email'
        );
    }

    /**
     * @throws RedisException
     */
    public function setProfileInToRedis(string $psn, AbstractDTO $user_info): void
    {
        $this->redis->hMSet(RedisKey::userPsn->value.$psn, [igbinary_serialize($user_info)]);
        $this->redis->expire(RedisKey::userPsn->value.$psn, 360);
    }

    /**
     * @throws RedisException
     */
    public function deleteProfileInToRedis(string $psn): void
    {
        $this->redis->hDel(RedisKey::userPsn->value.$psn, 0);
    }

    /**
     * @param string $psn
     * @return bool|ProfileInfoSchema
     * @throws RedisException
     */
    public function getProfileInToRedisByPsn(string $psn): bool|ProfileInfoSchema
    {
        $profile = $this->redis->hGetAll(RedisKey::userPsn->value.$psn);

        if (!$profile && empty($profile)) {
            return false;
        }

        $profile_info = igbinary_unserialize($profile[0]);

        return new ProfileInfoSchema(
            psn: $profile_info->psn,
            firstName: $profile_info->firstName,
            lastName: $profile_info->lastName,
            patronymic: $profile_info->patronymic,
            gender: $profile_info->gender,
            dateBirth: $profile_info->dateBirth,
            created: null,
        );
    }
}
