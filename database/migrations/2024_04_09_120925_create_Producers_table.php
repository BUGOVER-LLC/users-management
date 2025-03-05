<?php

declare(strict_types=1);

use App\Domain\Producer\Model\Producer;
use App\Domain\System\Model\System;
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
        Schema::create(Producer::getTableName(), function (Blueprint $table) {
            $table->increments(Producer::getPrimaryName());
            $table->string('username', 100)->nullable()->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('rememberToken', 200)->nullable();
            $table->dateTime('verifiedAt');
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
        Schema::dropIfExists(Producer::getTableName());
    }
};
