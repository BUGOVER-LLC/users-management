<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\System\Http\Controller\DeleteClientController;
use App\Domain\System\Http\Controller\GetClientsController;
use App\Domain\System\Http\Controller\StoreEnvironment;
use App\Domain\System\Http\Controller\StoreSystemData;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'system', 'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value]],
    static fn() => [
        Route::get('/', GetClientsController::class),
        Route::post('client', StoreSystemData::class),
        Route::delete('client/{clientId}/{revoke?}', DeleteClientController::class),
        Route::post('environment', StoreEnvironment::class),
    ]
);
