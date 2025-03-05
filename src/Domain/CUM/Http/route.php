<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\CUM\Http\Controller\ChangePasswordNoResidentController;
use App\Domain\CUM\Http\Controller\CitizenController;
use App\Domain\CUM\Http\Controller\ForgotPasswordController;
use App\Domain\CUM\Http\Controller\NidController;
use App\Domain\CUM\Http\Controller\NoResidentController;
use App\Domain\CUM\Http\Controller\PasswordChangeController;
use App\Domain\CUM\Http\Controller\ProfileController;
use App\Domain\CUM\Http\Controller\ProfileUpdateController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['middleware' => ['api']],
    function () {
        Route::controller(CitizenController::class)
            ->middleware(['auth:' . AuthGuard::apiCitizens->value])
            ->group(function () {
                Route::get('me', 'getCitizen');
                Route::put('complete-registration/{userId}', 'completeRegistration');
            });

        Route::prefix('profile')
            ->name('profile.')
            ->middleware(['auth:' . AuthGuard::apiCitizens->value . ',' . AuthGuard::apiUsers->value])
            ->group(function () {
                Route::get('/', ProfileController::class)
                    ->name('info');

                Route::put('update', ProfileUpdateController::class)
                    ->name('update');

                Route::put('password/change', PasswordChangeController::class)
                    ->name('password.change');
            });

        Route::middleware(['guest:' . AuthGuard::apiCitizens->value])
            ->group(function () {
                Route::controller(NidController::class)
                    ->group(function () {
                        Route::get('nid/login', 'login');
                        Route::get('nid/callback', 'callback');
                        Route::post('nid/token', 'token');
                        Route::post('user/token', 'userToken'); // @TODO Temporary
                    });

                Route::post('login', [CitizenController::class, 'login']);

                Route::controller(NoResidentController::class)
                    ->group(function () {
                        Route::post('sign-up', 'signUp');
                        Route::get('invite/accept', 'inviteAccept');
                    });

                Route::prefix('password-reset')
                    ->group(function () {
                        Route::post('request', ForgotPasswordController::class);
                        Route::put('reset', ChangePasswordNoResidentController::class);
                    });
            });
    }
);
