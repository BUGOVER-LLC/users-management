<?php

declare(strict_types=1);

use App\Domain\Attribute\Http\Controller\AttributesController;
use Illuminate\Support\Facades\Route;

Route::group([], static fn() => [
    Route::get('{resource}/attributes', AttributesController::class),
]);
