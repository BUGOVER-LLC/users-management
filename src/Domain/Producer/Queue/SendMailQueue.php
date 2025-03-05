<?php

declare(strict_types=1);

namespace App\Domain\Producer\Queue;

use App\Core\Enum\EmailType;
use App\Domain\Producer\Mail\AcceptCode;
use App\Domain\UMAC\Mail\SendInviteUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailQueue implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly string|EmailType $context,
        private readonly string $address,
        private readonly array $body = []
    )
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        match ($this->context) {
            EmailType::acceptCode->value => Mail::to($this->address)->send(new AcceptCode($this->address, $this->body)),
            EmailType::inviteUser->value => Mail::to($this->address)->send(new SendInviteUser($this->address, $this->body)),
            default => Log::info('Email context type invalid'),
        };
    }
}
