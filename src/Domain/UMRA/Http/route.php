<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\UMRA\Http\Controller\AttributeController;
use App\Domain\UMRA\Http\Controller\ResourcesController;
use App\Domain\UMRA\Http\Controller\RoomController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'umra', 'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value]],
    static fn(): array => [
        Route::get('attributes', [AttributeController::class, 'getAttributes'])
            ->name('attribute.attributes-get'),

        Route::post('attribute', [AttributeController::class, 'storeAttribute'])
            ->name('attribute.store-attribute'),

        Route::put('attribute/{attributeId}', [AttributeController::class, 'editAttribute'])
            ->name('attribute.edit-attribute'),

        Route::delete('attribute/{attributeId}', [AttributeController::class, 'deleteAttribute'])
            ->name('attribute.delete-attribute'),

        Route::get('resources', [ResourcesController::class, 'getResources'])
            ->name('attribute.get-resources'),

        Route::post('resource', [ResourcesController::class, 'createResource'])
            ->name('resources.resources-create'),

        Route::put('resource/{resourceId}', [ResourcesController::class, 'editResource'])
            ->name('resources.edit-resources'),

        Route::delete('resource/{resourceId}', [ResourcesController::class, 'deleteResource'])
            ->name('resources.delete-resource'),

        Route::get('rooms/{attributeId}', [RoomController::class, 'getRooms'])
            ->name('get-rooms'),

        Route::put('rooms/{attributeId}', [RoomController::class, 'setRooms'])
            ->name('set-rooms'),

        Route::post('room', [RoomController::class, 'createRoom'])
            ->name('create-rooms'),
    ]
);
