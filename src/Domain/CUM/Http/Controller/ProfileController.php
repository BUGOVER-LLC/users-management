<?php

declare(strict_types=1);

namespace App\Domain\CUM\Http\Controller;

use App\Domain\CUM\Action\FindAddressAction;
use App\Domain\CUM\Http\Resource\ProfileUpdateResponse;
use App\Domain\CUM\Model\Citizen;
use App\Domain\UMAC\Model\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Exceptions\ServerErrorException;
use Infrastructure\Http\Controllers\Controller;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

final class ProfileController extends Controller
{
    /**
     * @throws ServerErrorException
     * @throws GuzzleException
     */
    public function __invoke(FindAddressAction $action): ProfileUpdateResponse
    {
        try {
            $person = Auth::user();

            if (!$person) {
                throw new RuntimeException('Unauthorized', 401);
            }

            if ($person instanceof Citizen) {
                $address = $action->run($person->uuid);

                $profile = [
                    'hasRegistrationAddress' => filled($address['registrationAddress']),
                    'fullName' => $person->profile->fullName,
                    'psn' => $person->profile->psn,
                    'birthDate' => $person->profile->dateBirth,
                    'registrationAddress' => $address['registrationAddress'],
                    'phone' => $person->phone,
                    'email' => $person->email,
                    'avatar' => $person->avatar(),
                    'notificationAddressOrigin' => $address['notificationAddressOrigin'],
                    'notificationRegion' => $address['notificationRegion'],
                    'notificationCommunity' => $address['notificationCommunity'],
                    'notificationAddress' => $address['notificationAddress'],
                ];
            } elseif ($person instanceof User) {
                $profile = [
                    'fullName' => $person->profile->fullName,
                    'psn' => $person->profile->psn,
                    'birthDate' => $person->profile->dateBirth,
                    'phone' => $person->phone,
                    'email' => $person->email,
                    'avatar' => $person->avatar(),
                ];
            }

            return (new ProfileUpdateResponse($profile))
                ->additional([
                    'status' => ResponseStatus::HTTP_NO_CONTENT,
                ]);
        } catch (Exception $exception) {
            throw new ServerErrorException($exception->getMessage());
        }
    }
}
