<?php

declare(strict_types=1);

use App\Domain\Micro\Http\Controller\AuthApiClient;
use App\Domain\Micro\Http\Controller\FindDocumentController;
use App\Domain\Micro\Http\Controller\GetJudgesByResourceController;
use App\Domain\Micro\Http\Controller\GetJudgesSubordinatesController;
use App\Domain\Micro\Http\Controller\GetUserAttributeValueController;
use App\Domain\Micro\Http\Controller\GetUsersByAttrbiuteRoleController;
use App\Domain\Micro\Http\Controller\LoginByUuidController;
use App\Domain\Micro\Http\Controller\LogoutController;
use App\Domain\Micro\Http\Controller\PsnController;
use App\Domain\Micro\Http\Controller\SyncDataController;
use App\Domain\Micro\Http\Controller\UserAccessController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'micro-client'],
    static fn() => [
        Route::post('auth', AuthApiClient::class),
    ]
);

Route::group(
    ['prefix' => 'micro', 'middleware' => ['client']],
    static fn(): array => [
        Route::get('citizen/info/{documentValue}/{documentType}', [PsnController::class, 'getUserInfoByPsn']),
        Route::post('citizen/store', [PsnController::class, 'storeCitizen']),
        Route::post('citizen/store-absence', [PsnController::class, 'storeCitizenByDocType']),
        Route::post('logout', LogoutController::class),
        Route::post('login-by-uuid', LoginByUuidController::class),
        Route::get('find-document', FindDocumentController::class),
        Route::get('users/{uuid}/get-attribute-value', GetUserAttributeValueController::class),
        Route::get('users/{attributeValue}/{roleValue}', GetUsersByAttrbiuteRoleController::class),
        Route::get('user/access/{uuid?}', UserAccessController::class)->name('user.access-uuid'),
        Route::put('sync/data/{type}', SyncDataController::class)->name('sync.data'),
    ]
);

Route::group(
    ['prefix' => 'micro'],
    static fn() => [
        Route::get('judges/{resourceValue}', GetJudgesByResourceController::class),
        Route::get('judge/{judgeUuid}/subordinates', GetJudgesSubordinatesController::class),
    ]
);
