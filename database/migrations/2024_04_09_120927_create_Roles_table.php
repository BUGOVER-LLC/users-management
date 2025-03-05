<?php

declare(strict_types=1);

use App\Domain\Oauth\Model\Client;
use App\Domain\System\Model\System;
use App\Domain\UMRP\Model\Role;
use Illuminate\Database\Migrations\Migration;
use Infrastructure\Illuminate\Database\Schema;
use Infrastructure\Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Role::getTableName(), function (Blueprint $table) {
            $table->increments('roleId');
            $table
                ->foreignId('systemId')
                ->index('index'.Role::getTableName().'systemId')
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('clientId')
                ->nullable()
                ->index('index'.Role::getTableName().'clientId')
                ->constrained(
                    (new Client())->getTable(),
                    (new Client())->getKeyName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('roleName', 150);
            $table->string('roleValue', 100);
            $table->string('roleDescription', 500)->nullable()->index('indexrolesdescription');
            $table->boolean('roleActive')->default(true);
            $table->boolean('hasSubordinates')->default(false);
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
        Schema::dropIfExists(Role::getTableName());
    }
};
