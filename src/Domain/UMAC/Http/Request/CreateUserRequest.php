<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AuthGuard;
use App\Domain\UMAC\Http\DTO\CreateUserDTO;
use App\Domain\UMAC\Model\User;
use App\Domain\UMRA\Model\Attribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Infrastructure\Illuminate\Redis\RedisRepository;
use Override;

/**
 * @property string|Carbon $dateBirth
 * @property string $patronymic
 * @property string $firstName
 * @property string $lastName
 * @property string $psn
 * @property ?string $username
 * @property null|int $roleId
 * @property bool $active
 * @property string $email
 * @property ?int $attributeId
 * @property ?int $parentId
 */
class CreateUserRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(AuthGuard::webProducer->value)->check();
    }

    #[Override]
    public function rules(): array
    {
        return [
            'psn' => [
                'required',
                'string',
                'max:10',
                'min:10',
            ],
            'firstName' => [
                'required',
                'string',
                'max:100',
                'min:3',
            ],
            'lastName' => [
                'required',
                'string',
                'max:100',
                'min:3',
            ],
            'patronymic' => [
                'nullable',
                'string',
                'max:100',
                'min:3',
            ],
            'dateBirth' => [
                'required',
                'date_format:Y-m-d',
            ],
            'email' => [
                'required',
                'email:rfc',
                'unique:Users,email',
            ],
            'roleId' => [
                'nullable',
                'required',
                'int',
            ],
            'attributeId' => [
                'required_if:judgeId,null',
                'sometimes',
                'nullable',
                'int',
                'exists:' . Attribute::getTableName() . ',' . Attribute::getPrimaryName(),
            ],
            'parentId' => [
                'required_if:attributeId,null',
                'sometimes',
                'nullable',
                'int',
                'exists:' . User::getTableName() . ',' . User::getPrimaryName(),
            ],
            'active' => [
                'nullable',
                'required',
                'bool',
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'dateBirth' => Carbon::createFromFormat('Y-m-d', $this->dateBirth)->toDateString(),
        ]);
    }

    /**
     * Class BookkeepingCompanyPaginateRequest
     *
     * @package App\Http\Requests\SystemWorker
     * @method void moreValidation(Validator $validator)
     * @method bool authorize()
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(callback: function () use ($validator) {
            $info = resolve(RedisRepository::class)->getProfileInToRedisByPsn($this->psn);

            if (!$info) {
                $validator->errors()->add('psn', message: trans('users.psn_undefined', ['psn' => $this->psn]));

                return;
            }

            $data_nt_equals = $info->dateBirth !== $this->dateBirth
                || $info->firstName !== $this->firstName
                || $info->lastName !== $this->lastName
                || (($info->patronymic) && $info->patronymic !== $this->patronymic);

            if ($data_nt_equals) {
                $validator->errors()->add('psn', trans('users.psn_undefined', ['psn' => $this->psn]));
            }
        });
    }

    #[Override]
    public function toDTO(): CreateUserDTO
    {
        $dto = new CreateUserDTO(
            (int) $this->psn,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->patronymic,
            $this->dateBirth,
            $this->roleId,
            $this->attributeId,
            $this->parentId,
            $this->active,
        );
        $dto->setUser(Auth::user());

        return $dto;
    }
}
