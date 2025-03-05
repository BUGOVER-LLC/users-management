<?php

declare(strict_types=1);

use App\Core\Enum\AuthGuard;
use App\Domain\UMRP\Http\Controller\PermissionController;
use App\Domain\UMRP\Http\Controller\RolesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web', 'auth:' . AuthGuard::webProducer->value],
    'prefix' => 'umrp',
    'name' => 'umrp',
    'as' => 'umrp.',
], function () {
    Route::get('roles', [RolesController::class, 'roles'])
        ->name('roles');

    Route::get('roles/pager', [RolesController::class, 'rolesPager'])
        ->name('roles.pager');

    Route::get('role/info/{roleId}', [RolesController::class, 'infoRole'])
        ->name('role.info');

    Route::post('role/create', [RolesController::class, 'create'])
        ->name('roles.create');

    Route::put('role/update/{roleId}', [RolesController::class, 'update'])
        ->name('roles.update');

    Route::delete('role/delete/{roleId}', [RolesController::class, 'delete'])
        ->name('roles.delete');

    Route::get('permissions', [PermissionController::class, 'permissions'])
        ->name('permissions');

    Route::get('permissions/free', [PermissionController::class, 'permissionsFree'])
        ->name('permissions.free');

    Route::post('permission/create', [PermissionController::class, 'create'])
        ->name('permission.create');

    Route::put('permission/update/{permissionId}', [PermissionController::class, 'update'])
        ->name('permission.update.permissionId');

    Route::delete('permission/delete/{permissionId}', [PermissionController::class, 'delete'])
        ->name('permission.delete.permissionId');
});
