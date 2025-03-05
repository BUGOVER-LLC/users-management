<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\Producer\Http\Controller\BoardController;
use App\Domain\Producer\Http\Controller\EnvironmentController;
use App\Domain\Producer\Http\Controller\IndexController;
use App\Domain\Producer\Http\Controller\PasswordConfirmController;
use App\Domain\Producer\Http\Controller\ProfileController;
use App\Domain\Producer\Http\Controller\SendCodeController;
use App\Domain\Producer\Http\Controller\SendEmailController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'producer/profile',
        'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value],
        'name' => 'producer.profile',
        'as' => 'producer.profile.',
    ],
    static fn() => [
        Route::get('', [ProfileController::class, 'profile'])->name('profile'),
        Route::post('logout', [ProfileController::class, 'logout'])->name('profile.logout'),
        Route::put('update/{profileId}', [ProfileController::class, 'update'])->name('update'),
    ]
);

Route::group(
    [
        'middleware' => ['web', 'guest:' . AuthGuard::webProducer->value, 'set_auth_payload'],
        'prefix' => 'producer/auth',
        'name' => 'authProducer',
        'as' => 'authProducer.',
    ],
    static fn() => [
        Route::get('/', IndexController::class)
            ->name('index'),

        Route::post('send-email', SendEmailController::class)
            ->name('send-email'),

        Route::post('send-code', SendCodeController::class)
            ->name('send-code'),

        Route::post('confirm-secret', PasswordConfirmController::class)
            ->name('confirm-password'),

        Route::get('{any}', IndexController::class)->where('any', '.*'),
    ]
);

Route::group(
    [
        'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value],
        'prefix' => 'producer/board',
        'name' => 'producerBoard',
        'as' => 'producerBoard.',
    ],
    static fn() => [
        Route::get('/', BoardController::class)->name('index'),
        Route::get('{any}', BoardController::class)->where('any', '.*'),
    ]
);

Route::group(
    [
        'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value],
        'prefix' => 'producer/started',
        'name' => 'startedProducer',
        'as' => 'startedProducer.',
    ],
    static fn() => [
        Route::get('environment', EnvironmentController::class)
            ->name('setEnvironment'),

        Route::get('{any}', EnvironmentController::class)->where('any', '.*'),
    ]
);
