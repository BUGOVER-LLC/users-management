<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Resource;

use App\Core\Abstract\AbstractResource;
use App\Core\Abstract\AbstractSchema;
use App\Domain\CUM\Enum\AddressOriginType;
use App\Domain\CUM\Http\Schema\ProfileSchema;
use Illuminate\Http\Request;

/**
 * @property-read array $resource
 */
final class ProfileUpdateResponse extends AbstractResource
{
    public function toSchema(Request $request): AbstractSchema
    {
        if (! isset($this->resource['notificationAddressOrigin'])) {
            return new ProfileSchema(
                hasRegistrationAddress: false,
                fullName: $this->resource['fullName'],
                psn: $this->resource['psn'],
                birthDate: $this->resource['birthDate'],
                registrationAddress: false,
                phone: $this->resource['phone'],
                email: $this->resource['email'],
                notificationAddressOrigin: false,
                notificationRegion: false,
                notificationCommunity: false,
                notificationAddress: false,
                avatar: $this->resource['avatar'],
                canUpdateAddress: false,
            );
        }

        return new ProfileSchema(
            hasRegistrationAddress: $this->resource['hasRegistrationAddress'],
            fullName: $this->resource['fullName'],
            psn: $this->resource['psn'],
            birthDate: $this->resource['birthDate'],
            registrationAddress: $this->resource['registrationAddress'],
            phone: $this->resource['phone'],
            email: $this->resource['email'],
            notificationAddressOrigin: $this->resource['notificationAddressOrigin']
                ? AddressOriginType::from($this->resource['notificationAddressOrigin'])
                : null,
            notificationRegion: $this->resource['notificationRegion'],
            notificationCommunity: $this->resource['notificationCommunity'],
            notificationAddress: $this->resource['notificationAddress'],
            avatar: $this->resource['avatar'],
        );
    }
}
