<?php

declare(strict_types=1);

namespace App\Core\Api\Ekeng;

use App\Core\Api\Ekeng\DTO\EkgDTO;
use App\Core\Api\Ekeng\DTO\EkgPassportDTO;
use App\Core\Enum\AccessDocumentType;
use App\Core\Enum\PersonType;
use App\Domain\UMAC\Exception\CitizenIsDeadException;
use App\Domain\UMAC\Exception\ExpiredPassportException;
use App\Domain\UMAC\Exception\IdentityDocumentDoesntExistsException;
use App\Domain\UMAC\Exception\UserPsnDoesntExistsException;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Infrastructure\Exceptions\ServerErrorException;

final class EkengApi
{
    private const string CODE_UNDEFINED_PSN = 'module.500';

    private const string STATUS_UNDEFINED_PSN = 'failed';

    private object $person;

    private readonly string|int $documentValue;

    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @throws ServerErrorException
     * @throws GuzzleException
     * @throws UserPsnDoesntExistsException
     * @throws ExpiredPassportException
     * @throws IdentityDocumentDoesntExistsException
     * @throws \JsonException
     */
    public function send(string|int $docValue, AccessDocumentType $documentType): EkengApi
    {
        $this->documentValue = $docValue;

        $formParams = AccessDocumentType::document->value === $documentType->value
            ? ['docnum' => $docValue]
            : ['psn' => $docValue];

        try {
            $request = $this->client->post(config('app.service_ekeng_url'), [
                'form_params' => $formParams,
                'connect_timeout' => '10',
                'timeout' => '20',
            ]);

            $content = json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            logging($exception);

            throw new ServerErrorException();
        }
        Log::info(json_encode($content));
        if ($content->code === self::CODE_UNDEFINED_PSN || $content->status === self::STATUS_UNDEFINED_PSN) {
            Log::info(json_encode($content, JSON_THROW_ON_ERROR));
            if ($documentType->is(AccessDocumentType::document)) {
                throw new IdentityDocumentDoesntExistsException((string) $docValue);
            }

            throw new UserPsnDoesntExistsException((string) $docValue);
        }

        $this->person = $content->result[0];

        if ($this->person->IsDead) {
            throw CitizenIsDeadException::withMessages([
                'documentValue' => __('users.is_dead'),
            ]);
        }

        if ($documentType->is(AccessDocumentType::document) && !$this->documentIsActual($docValue)) {
            throw ExpiredPassportException::withMessages([
                'documentValue' => __('users.id_expired', ['serialNumber' => $docValue]),
            ]);
        }

        return $this;
    }

    /**
     * @return EkgDTO
     */
    public function getPersonInfo(): EkgDTO
    {
        $documents = [];
        $validDocuments = $this->validDocuments();

        $primaryPassport = collect($validDocuments)
            ->whereIn('Document_Status', ['PRIMARY_VALID', 'VALID'], true)
            ->first();

        $primaryAddress = collect($this->person->AVVAddresses->AVVAddress)
            ->firstWhere(
                fn($address) => 'CURRENT' === $address->RegistrationData->Registration_Type
                    && 'P' === $address->RegistrationData->Registration_Status
            );

        if (!$primaryAddress) {
            $primaryAddress = collect($this->person->AVVAddresses->AVVAddress)->first();
        }

        foreach ($validDocuments as $validDocument) {
            if ($validDocument->Document_Number === $this->documentValue) {
                $identifierDocumentType = $validDocument->Document_Type;
            }

            $documents[] = new EkgPassportDTO(
                type: $validDocument->Document_Type,
                status: $validDocument->Document_Status,
                serialNumber: $validDocument->Document_Number,
                dateIssue: $this->formatDate($validDocument->PassportData->Passport_Issuance_Date),
                dateExpiry: $this->formatDate($validDocument->PassportData->Passport_Validity_Date),
                authority: $validDocument->Document_Department,
                citizenship: $validDocument->Person->Citizenship->Citizenship[0]->CountryShortName ?? 'ARM',
                photo: $validDocument?->Photo_ID,
                isPrimary: $primaryPassport->Document_Number === $validDocument->Document_Number,
            );
        }

        return new EkgDTO(
            psn: $this->person->PNum,
            firstName: $primaryPassport->Person->First_Name,
            lastName: $primaryPassport->Person->Last_Name,
            patronymic: $primaryPassport->Person->Patronymic_Name,
            gender: $primaryPassport->Person->Genus,
            dateBirth: $this->formatDate($primaryPassport->Person->Birth_Date),
            deathDate: $this->formatDate($this->person->DeathDate),
            region: $primaryAddress->RegistrationAddress->Region,
            community: $primaryAddress->RegistrationAddress->Community,
            registrationAddress: $this->registrationAddress($primaryAddress),
            documents: $documents,
            personType: $validDocument ? PersonType::RESIDENT : PersonType::UNDEFINED,
            identifierDocumentType: $identifierDocumentType ?? null,
        );
    }

    protected function validDocuments(): array
    {
        return array_filter(
            $this->person->AVVDocuments->Document,
            static fn(object $item) => \in_array($item->Document_Status, ['VALID', 'PRIMARY_VALID']),
        );
    }

    protected function documentIsActual(string $documentValue): bool
    {
        $documents = $this->validDocuments();

        foreach ($documents as $document) {
            if ($documentValue === $document->Document_Number) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string|null $date
     * @return string|null
     */
    protected function formatDate(null|string $date): ?string
    {
        if (!$date) {
            return null;
        }

        return Carbon::createFromFormat('d/m/Y', $date)->toDateString();
    }

    /**
     * @param object $primaryAddress
     *
     * @return string
     */
    private function registrationAddress(object $primaryAddress): string
    {
        $address = [
            $primaryAddress->RegistrationAddress->Region,
            $primaryAddress->RegistrationAddress->Community,
            $primaryAddress->RegistrationAddress->Street,
            $primaryAddress->RegistrationAddress->Building,
            $primaryAddress->RegistrationAddress->Building_Type,
            $primaryAddress->RegistrationAddress->Apartment,
        ];

        return collect($address)->join(' ');
    }
}
