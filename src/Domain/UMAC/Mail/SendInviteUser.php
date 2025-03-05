<?php

declare(strict_types=1);

namespace App\Domain\UMAC\Mail;

use Illuminate\Mail\Mailable;

class SendInviteUser extends Mailable
{
    public function __construct(private readonly string $froms, private readonly array $body)
    {
    }

    /**
     * @return SendInviteUser
     */
    public function build(): SendInviteUser
    {
        return $this
            ->to($this->froms)
            ->with('invite_url', $this->body['domain'] . '?' . $this->body['query'])
            ->markdown('mail.invite-user');
    }
}
