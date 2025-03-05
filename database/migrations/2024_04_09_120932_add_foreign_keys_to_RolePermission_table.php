<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('RolePermission', function (Blueprint $table) {
            $table->foreign(['permissionId'], 'foreign_role_permission_permissions_permission_id')->references(
                ['permissionId']
            )->on('Permissions')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['roleId'], 'foreign_role_permission_roles_role_id')->references(['roleId'])->on(
                'Roles'
            )->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('RolePermission', function (Blueprint $table) {
            $table->dropForeign('foreign_role_permission_permissions_permission_id');
            $table->dropForeign('foreign_role_permission_roles_role_id');
        });
    }
};
