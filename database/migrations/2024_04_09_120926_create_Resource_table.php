<?php

declare(strict_types=1);

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
    public function up(): void
    {
        Schema::create(Resource::getTableName(), function (Schema\Blueprint $table) {
            $table->increments(Resource::getPrimaryName());
            $table
                ->foreignId('systemId')
                ->index('index'.System::getTableName().'systemId')
                ->nullable()
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('resourceName');
            $table->string('resourceValue');
            $table->string('resourceDescription')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(Resource::getTableName());
    }
};
