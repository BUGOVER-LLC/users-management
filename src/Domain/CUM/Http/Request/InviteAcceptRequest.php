<?php

namespace App\Domain\CUM\Http\Request;

use App\Core\Abstract\AbstractRequest;
use App\Domain\CUM\Http\DTO\InviteAcceptDTO;

class InviteAcceptRequest extends AbstractRequest
{

    /**
     * @inheritDoc
     */
    #[\Override] public function rules(): array
    {
       return [
           'email' => [
               'required',
               'email:rfc',
               'exists:InvitationCitizens,inviteEmail',
           ],
           'token' => [
               'required',
               'string',
               'exists:InvitationCitizens,inviteToken',
           ],
       ];
    }

    #[\Override] public function toDTO(): InviteAcceptDTO
    {
        return new InviteAcceptDTO(
            $this->token,
            $this->email
        );

    }
}
