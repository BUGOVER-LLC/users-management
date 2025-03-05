<?php

namespace App\Domain\CUM\Mail;

use Illuminate\Mail\Mailable;

class AcceptCode extends Mailable
{
    public function __construct(
        private readonly string $froms,
        private readonly array $body
    )
    {
    }

    public function build(): AcceptCode
    {
        return $this
            ->to($this->froms)
            ->with(['accept_code' => $this->body['accept_code']])
            ->markdown('mail.accept-code', $this->body);
    }
}
