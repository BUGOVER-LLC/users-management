<?php

declare(strict_types=1);

namespace App\Domain\Micro\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\Micro\Http\DTO\AuthApiClientDTO;
use App\Domain\Oauth\Model\Client;
use App\Domain\System\Model\System;
use Illuminate\Support\Facades\Log;

class AuthApiClientRequest extends AbstractRequest
{
    /**
     * Class BookkeepingCompanyPaginateRequest
     *
     * @package App\Http\Requests\SystemWorker
     * @method void moreValidation(\Illuminate\Validation\Validator $validator)
     * @method bool authorize()
     */
    public function authorize(): true
    {
        return true;
    }

    #[\Override] public function rules(): array
    {
        Log::info('clientDomainName:' . $this->domain);

        return [
            'secret' => [
                'required',
                'string',
                'exists:' . (new Client())->getTable() . ',secret',
            ],
            'domain' => [
                'required',
                'string',
//                'exists:' . (new System())->getTable() . ',systemDomain',
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
        $this->merge([
            'domain' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
        ]);
    }

    #[\Override] public function toDTO(): AuthApiClientDTO
    {
        return new AuthApiClientDTO(
            $this->secret,
            $this->domain
        );
    }
}
