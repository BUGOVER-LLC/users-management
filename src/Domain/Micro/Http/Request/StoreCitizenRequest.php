<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Enum\AddressOriginType;
use App\Domain\Micro\Http\DTO\StoreCitizenDTO;
use Illuminate\Validation\Rules\Enum;
use Override;

class StoreCitizenRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function rules(): array
    {
        return [
            'documentType' => [
                'nullable',
                'required',
            ],
            'documentValue' => [
                'required',
            ],
            'firstName' => [
                'required',
            ],
            'lastName' => [
                'required',
            ],
            'patronymic' => [
                'nullable',
                'string',
                'max:50',
            ],
            'dateBirth' => [
                'nullable',
                'date',
            ],
            'gender' => [
                'nullable',
            ],
            'personType' => [
                'required',
            ],
            'email' => [
                'nullable',
                'email:rfc',
            ],
            'phone' => [
                'nullable',
            ],
            'notificationAddressOrigin' => [
                'required',
                'string',
                new Enum(AddressOriginType::class),
            ],
            'notificationRegion' => [
                'required_if:origin,' . AddressOriginType::OTHER->value,
                'int',
                'exists:Regions,regionId',
            ],
            'notificationCommunity' => [
                'required_if:origin,' . AddressOriginType::OTHER->value,
                'int',
                'exists:Communities,communityId',
            ],
            'notificationAddress' => [
                'required_if:origin,' . AddressOriginType::OTHER->value,
                'string',
                'max:250',
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        if ($this->notificationAddressOrigin === AddressOriginType::OTHER->value) {
            $this->merge([
                'notificationCommunity' => (int) $this->notificationCommunity,
                'notificationRegion' => (int) $this->notificationRegion,
            ]);
        }
    }

    #[Override]
    public function toDTO(): StoreCitizenDTO
    {
        return new StoreCitizenDTO(
            $this->firstName,
            $this->lastName,
            $this->personType,
            $this->patronymic,
            $this->email,
            $this->phone,
            $this->dateBirth,
            $this->gender,
            $this->country,
            $this->citizenship,
            $this->documentType,
            $this->documentValue,
            $this->notificationAddressOrigin,
            $this->notificationRegion,
            $this->notificationCommunity,
            $this->notificationAddress,
        );
    }
}
