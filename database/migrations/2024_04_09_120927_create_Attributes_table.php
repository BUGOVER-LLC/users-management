<?php

declare(strict_types=1);

use App\Domain\Oauth\Model\Client;
use App\Domain\System\Model\System;
use App\Domain\UMRA\Model\Resource;
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
        Schema::create(\App\Domain\UMRA\Model\Attribute::getTableName(), function (Schema\Blueprint $table) {
            $table->increments('attributeId');

            $table
                ->foreignId('systemId')
                ->index('index'.\App\Domain\UMRA\Model\Attribute::getTableName().'systemId')
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('resourceId')
                ->index('index'.Resource::getTableName().'resourceId')
                ->nullable()
                ->constrained(
                    Resource::getTableName(),
                    Resource::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('clientId')
                ->nullable()
                ->index('index'.\App\Domain\UMRA\Model\Attribute::getTableName().'clientId')
                ->constrained(
                    (new Client())->getTable(),
                    (new Client())->getKeyName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('attributeName', 150);
            $table->string('attributeValue', 100);
            $table->string('attributeDescription', 1000)->nullable();
            $table->timestamp('updatedAt')->useCurrent();
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
        Schema::dropIfExists(\App\Domain\UMRA\Model\Attribute::getTableName());
    }
};
