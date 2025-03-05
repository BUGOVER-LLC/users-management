<?php

declare(strict_types=1);

use App\Domain\Producer\Model\Producer;
use App\Domain\System\Model\System;
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
        Schema::create(System::getTableName(), function (Schema\Blueprint $table) {
            $table->increments(System::getPrimaryName());
            $table
                ->foreignId('producerId')
                ->index('index'.System::getTableName().'producerId')
                ->constrained(
                    Producer::getTableName(),
                    Producer::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('systemName', 100)->unique();
            $table->string('systemDomain', 120)->nullable();
            $table->json('systemLogo')->nullable();

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
        Schema::dropIfExists(System::getTableName());
    }
};
