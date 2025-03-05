<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Illuminate\Database\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OauthAccessTokens', function (Schema\Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->unsignedInteger('client_id');
            $table
                ->foreignId('deviceId')
                ->index('index'.ClientDevice::getTableName().'deviceId')
                ->nullable()
                ->constrained(
                    ClientDevice::getTableName(),
                    ClientDevice::getPrimaryName(),
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->dateTime('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OauthAccessTokens');
    }
};
