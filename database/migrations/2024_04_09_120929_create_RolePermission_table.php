<?php

declare(strict_types=1);

use App\Core\Enum\Accessor;
use App\Domain\UMRP\Model\RolePermission;
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
        Schema::create(RolePermission::getTableName(), function (Blueprint $table) {
            $table->increments(RolePermission::getPrimaryName());
            $table->unsignedInteger('roleId')->index('foreign_role_permission_permissions_role_id');
            $table->unsignedInteger('permissionId')->index('foreign_role_permission_permissions_permission_id');
            $table->set('access', Accessor::all()->values()->all());
            $table->timestamp('createdAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('RolePermission');
    }
};
