<?php

declare(strict_types=1);

use App\Domain\Oauth\Model\Client;
use App\Domain\System\Model\System;
use App\Domain\UMRP\Model\Permission;
use Illuminate\Database\Migrations\Migration;
use Infrastructure\Illuminate\Database\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Permission::getTableName(), function (Schema\Blueprint $table) {
            $table->increments('permissionId');

            $table
                ->foreignId('systemId')
                ->index('index'.Permission::getTableName().'systemId')
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('clientId')
                ->nullable()
                ->index('index'.Permission::getTableName().'clientId')
                ->constrained(
                    (new Client())->getTable(),
                    (new Client())->getKeyName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('permissionName', 150);
            $table->string('permissionValue', 100);
            $table->string('permissionDescription', 500)->nullable()->index('indexpermissiondescription');
            $table->boolean('permissionActive')->default(true);
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
        Schema::dropIfExists(Permission::getTableName());
    }
};
