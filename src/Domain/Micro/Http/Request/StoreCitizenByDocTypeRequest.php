<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\AccessDocumentType;
use App\Domain\CUM\Enum\AddressOriginType;
use App\Domain\Micro\Http\DTO\StoreByDocumentDTO;
use Illuminate\Validation\Rules\Enum;
use Override;

/**
 * @property null|string $documentType
 * @property string $documentValue
 * @property null|string $notificationAddressOrigin
 * @property null|int $notificationRegion
 * @property null|int $notificationCommunity
 * @property null|string $notificationAddress
 * @property null|string $email
 * @property null|string $phone
 */
class StoreCitizenByDocTypeRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'documentType' => [
                'nullable',
                new Enum(AccessDocumentType::class),
            ],
            'documentValue' => [
                'required',
            ],
            'notificationAddressOrigin' => [
                'nullable',
                new Enum(AddressOriginType::class),
            ],
            'notificationRegion' => [
                'required_if:notificationAddressOrigin,' . AddressOriginType::OTHER->value,
                'int',
            ],
            'notificationCommunity' => [
                'required_if:notificationAddressOrigin,' . AddressOriginType::OTHER->value,
                'int',
            ],
            'notificationAddress' => [
                'required_if:notificationAddressOrigin,' . AddressOriginType::OTHER->value,
                'string',
            ],
            'email' => [
                'nullable',
                'email:rfc',
                'unique:Citizens,citizenId',
            ],
            'phone' => [
                'nullable',
                'string',
            ],
        ];
    }

    #[Override]
    public function toDTO(): StoreByDocumentDTO
    {
        return new StoreByDocumentDTO(
            $this->documentValue,
            $this->documentType,
            $this->email,
            $this->phone,
            $this->notificationAddressOrigin ? AddressOriginType::from($this->notificationAddressOrigin) : null,
            $this->notificationRegion,
            $this->notificationCommunity,
            $this->notificationAddress,
        );
    }
}
