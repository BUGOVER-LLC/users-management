<?php

declare(strict_types=1);

use App\Domain\Oauth\Http\Controller\CreatePasswordTokenClientController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'authorize', 'middleware' => ['auth:web_producer', 'web']], function () {
    Route::post('client', CreatePasswordTokenClientController::class)->name('create-token-client');
});
