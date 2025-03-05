<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Enum\AddressOriginType;
use App\Domain\CUM\Http\DTO\ProfileUpdateDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Rules\ExistsInUsersAndCitizens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Override;

/**
 * @property int $userId
 * @property string $phone
 * @property string $email
 * @property null|string $notificationAddressOrigin
 * @property null|int $notificationRegion
 * @property null|int $notificationCommunity
 * @property null|string $notificationAddress
 */
class ProfileUpdateRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                'integer',
                new ExistsInUsersAndCitizens(),
            ],
            'phone' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'email:rfc',
                Rule::when(
                    condition: Auth::user() instanceof Citizen,
                    rules: "unique:Citizens,email,$this->userId,citizenId",
                    defaultRules: "unique:Users,email,$this->userId,userId",
                ),
            ],
            'notificationAddressOrigin' => [
                Rule::when(
                    condition: Auth::user() instanceof Citizen,
                    rules: ['required', Rule::enum(AddressOriginType::class)],
                    defaultRules: ['sometimes'],
                ),
            ],
            'notificationRegion' => [
                Rule::requiredIf(
                    fn () => $this->notificationAddressOrigin
                        && $this->notificationAddressOrigin === AddressOriginType::OTHER->value,
                ),
                'int',
            ],
            'notificationCommunity' => [
                Rule::requiredIf(
                    fn () => $this->notificationAddressOrigin
                        && $this->notificationAddressOrigin === AddressOriginType::OTHER->value,
                ),
                'int',
            ],
            'notificationAddress' => [
                Rule::requiredIf(
                    fn () => $this->notificationAddressOrigin
                        && $this->notificationAddressOrigin === AddressOriginType::OTHER->value,
                ),
                'string',
                'max:250',
            ],
            '_method' => ['nullable']
        ];
    }

    #[Override]
    public function toDTO(): ProfileUpdateDTO
    {
        return new ProfileUpdateDTO(
            citizenId: $this->userId,
            email: $this->email,
            phone: $this->phone,
            notificationAddressOrigin: $this->notificationAddressOrigin
                ? AddressOriginType::fromValue($this->notificationAddressOrigin)
                : null,
            notificationRegion: $this->notificationRegion,
            notificationCommunity: $this->notificationCommunity,
            notificationAddress: $this->notificationAddress,
        );
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge([
            'userId' => Auth::user()->{Auth::user()->getKeyName()},
        ]);
    }

    #[Override]
    public function attributes(): array
    {
        return __('attribute.profile');
    }
}
