<?php

declare(strict_types=1);

use App\Domain\System\Model\System;
use App\Domain\UMRA\Model\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(Room::getTableName(), function (Blueprint $table) {
            $table->increments(Room::getPrimaryName());
            $table
                ->foreignId('systemId')
                ->index('index'.Room::getTableName().'systemId')
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('attributeId')
                ->index('index'.Room::getTableName().'attributeId')
                ->constrained(
                    \App\Domain\UMRA\Model\Attribute::getTableName(),
                    \App\Domain\UMRA\Model\Attribute::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('roomName', 150);
            $table->string('roomValue', 120);
            $table->string('roomDescription', 1000)->nullable();
            $table->timestamp('updatedAt')->useCurrent();
            $table->timestamp('createdAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('_rooms');
    }
};
