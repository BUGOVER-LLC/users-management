<?php

namespace App\Domain\CUM\Action;

use App\Core\Abstract\AbstractAction;
use App\Domain\CUM\Http\DTO\NoResidentDTO;
use App\Domain\CUM\Model\Citizen;
use App\Domain\CUM\Model\InvitationCitizen;
use App\Domain\CUM\Repository\CitizenRepository;
use App\Domain\CUM\Repository\InvitationCitizenRepository;
use App\Domain\UMAC\Model\Profile;
use App\Domain\UMAC\Repository\ProfileRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @method null|Citizen transactionalRun(NoResidentDTO $dto)
 */
class NoResidentSignUpAction extends AbstractAction
{
    public function __construct(
        public readonly ProfileRepository $profileRepository,
        public readonly CitizenRepository $citizenRepository,
        public readonly InvitationCitizenRepository $invitationCitizenRepository
    ) {
    }

    public function run(NoResidentDTO $dto): ?Citizen
    {
        return $this->createNoResident($dto);
    }

    /**
     * @param NoResidentDTO $dto
     * @return Profile
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Service\Repository\Exceptions\RepositoryException
     */
    private function createNoResident(NoResidentDTO $dto): ?Citizen
    {
        $profile = $this->profileRepository->create(
            [
                'firstName' => $dto->firstName,
                'lastName' => $dto->lastName,
                'patronymic' => $dto->patronymic,
                'dateBirth' => $dto->dateBirth,
                'residenceAddress' => $dto->residenceAddress,
                'registrationAddress' => $dto->registrationAddress,
                'gender' => $dto->gender,
                'citizen' => [
                    'email' => $dto->email,
                    'phone' => $dto->phone,
                    'documentType' => $dto->documentType,
                    'documentValue' => $dto->documentNumber,
                    'documentFile' => $dto->documentFile,
                    'notificationAddress' => $dto->notificationAddress,
                    'password' => Hash::make($dto->password),
                    'isActive' => true,
                    'isChecked' => false,
                ]
            ],
            true,
        );
        $invitation = $this->createInviteUrl($dto);
        $this->createInvitation($profile->citizen, $dto, $invitation);

        return $profile->citizen;
    }

    /**
     * @param NoResidentDTO $dto
     * @return array
     */
    private function createInviteUrl(NoResidentDTO $dto): array
    {
        $invite_token = Str::random(128);
        $query_string = http_build_query([
            'token' => $invite_token,
            'email' => $dto->email,
        ]);

        return [
            'domain' => config('app.url') . '/invite/accept',
            'token' => $invite_token,
            'query' => $query_string,
        ];
    }

    private function createInvitation(Citizen $citizen, NoResidentDTO $dto, array $invite_data): InvitationCitizen
    {
        $invitation = $this->invitationCitizenRepository->findByCitizenId($citizen->citizenId);
        if ($invitation) {
            $invitation->delete();
        }

        return $this->invitationCitizenRepository->create([
            'citizenId' => $citizen->citizenId,
            'inviteUrl' => $invite_data['domain'] . '?' . $invite_data['query'],
            'inviteToken' => $invite_data['token'],
            'inviteEmail' => $dto->email,
            'passed' => now()->toDateTimeString(),

        ]);
    }
}
