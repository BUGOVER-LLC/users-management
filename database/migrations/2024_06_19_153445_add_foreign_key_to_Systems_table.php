<?php

declare(strict_types=1);

use App\Domain\Producer\Model\Producer;
use App\Domain\System\Model\System;
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
        Schema::table(Producer::getTableName(), function (Blueprint $table) {
            $table
                ->foreignId('currentSystemId')
                ->nullable()
                ->after(Producer::getPrimaryName())
                ->index('index'.System::getTableName().'clientId')
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Producer::getTableName(), function (Blueprint $table) {
            //
        });
    }
};
