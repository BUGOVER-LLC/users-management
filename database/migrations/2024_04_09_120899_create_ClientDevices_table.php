<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Infrastructure\Eloquent\Model\ClientDevice;
use Infrastructure\Illuminate\Database\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable(ClientDevice::getTableName())) {
            Schema::create(ClientDevice::getTableName(), function (Schema\Blueprint $table) {
                $table->increments(ClientDevice::getPrimaryName());

                $table->unsignedInteger('clientId')->index('index' . ClientDevice::getTableName() . 'clientId');
                $table->string('clientType', 50)->index('index' . ClientDevice::getTableName() . 'clientType');
                $table->string('device', 250);
                $table->char('ip', 15)->nullable();
                $table->dateTime('loggedAt')->nullable();
                $table->dateTime('logoutAt')->nullable();

                $table->timestamp('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(ClientDevice::getTableName());
    }
};
