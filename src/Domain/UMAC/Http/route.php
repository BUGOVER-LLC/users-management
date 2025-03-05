<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\UMAC\Http\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value],
        'prefix' => 'umac',
        'name' => 'umac',
        'as' => 'umac.',
    ],
    static fn(): array => [
        Route::get('users', [UserController::class, 'userInvitations'])
            ->name('users'),

        Route::get('users/{roleValue}', [UserController::class, 'usersByRole'])
            ->name('user.role-by-users'),

        Route::get('user-invitations/{userId}', [UserController::class, 'getInvitations'])
            ->name('user-invitations'),

        Route::post('user/invite', [UserController::class, 'createInvite'])
            ->name('user.create'),

        Route::put('user/edit/{userId}', [UserController::class, 'editUser'])
            ->name('edit.user'),

        Route::delete('user/invite/{userId}', [UserController::class, 'deleteInvite'])
            ->name('user.invite.userId'),

        Route::get('user/info/{psn}', [UserController::class, 'userCheck'])
            ->name('user.check.psn'),
    ]
);
