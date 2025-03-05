<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\UMAA\Http\Controller\AcceptInviteUser;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => ['api', 'guest:' . AuthGuard::apiUsers->value],
        'prefix' => 'umaa',
        'name' => 'user.accept',
        'as' => 'user.accept.',
    ],
    static fn() => [
        Route::post('invite/accept', AcceptInviteUser::class)->name('accept.invite')
    ],
);
