<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Core\Enum\DocumentType;
use App\Core\Enum\Gender;
use App\Domain\CUM\Http\DTO\NoResidentDTO;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Override;

/**
 * @property string $firstName
 * @property string $lastName
 * @property string $patronymic
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $passwordConfirmation
 * @property string $notificationAddress
 * @property string|Carbon $dateBirth
 * @property string $registrationAddress
 * @property string $residenceAddress
 * @property string|Gender $gender
 * @property string|DocumentType $documentType
 * @property string $documentNumber
 * @property UploadedFile $documentFile
 */
class NoResidentSignUpRequest extends AbstractRequest
{
    #[Override]
    public function rules(): array
    {
        return [
            'firstName' => [
                'required',
                'max:255',
            ],
            'lastName' => [
                'required',
                'max:255',
            ],
            'patronymic' => [
                'nullable',
                'max:255',
            ],
            'email' => [
                'required',
                'email:rfc',
                'unique:Citizens,email',
            ],
            'phone' => [
                'required',
            ],
            'password' => [
                'required',
                'same:passwordConfirmation',
                Password::defaults(),
            ],
            'passwordConfirmation' => [
                'required',
            ],
            'notificationAddress' => [
                'required',
                'max:255',
            ],
            'residenceAddress' => [
                'required',
                'max:255',
            ],
            'registrationAddress' => [
                'required',
                'max:255',
            ],
            'dateBirth' => [
                'required',
                'date',
            ],
            'gender' => [
                'required',
                new Enum(Gender::class),
            ],
            'documentNumber' => [
                'required',
                'max:255',
                'unique:Citizens,documentValue',
            ],
            'documentType' => [
                'required',
                new Enum(DocumentType::class),
            ],
            'documentFile' => [
//                'required',
                'nullable',
                File::defaults(),
            ],
        ];
    }

    public function attributes(): array
    {
        return __('attribute.noResidentSign');
    }





    #[Override]
    public function toDTO(): NoResidentDTO
    {
        return new NoResidentDTO(
            $this->firstName,
            $this->lastName,
            $this->patronymic,
            $this->email,
            $this->phone,
            $this->password,
            $this->passwordConfirmation,
            $this->notificationAddress,
            $this->dateBirth,
            $this->residenceAddress,
            $this->registrationAddress,
            $this->gender,
            $this->documentType,
            $this->documentNumber,
            $this->documentFile,
        );
    }
}
