<?php

declare(strict_types=1);

namespace App\Console;

use App\Domain\UMAC\Model\InvitationUser;
use App\Domain\UMAC\Repository\InvitationUserRepository;
use Illuminate\Console\Command;

class DeleteOldestUserInvites extends Command
{
    public $signature = 'deleteOldestInvite';

    public $description = '';

    public function __construct(private readonly InvitationUserRepository $invitationUserRepository)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $invites = $this->invitationUserRepository->getOldestInvitationsByDay(3);

        if (!$invites->count()) {
            $this->info("Doesn't there exist an invitation for deleting now?");
            return;
        }

        $invites->each(fn(InvitationUser $item) => $item->delete());
        $this->invitationUserRepository->forgetCache();

        $this->info('Oldest invitation deleted success');
    }
}
