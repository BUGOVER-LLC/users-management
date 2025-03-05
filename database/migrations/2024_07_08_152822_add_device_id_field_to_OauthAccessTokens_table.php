<?php

declare(strict_types=1);

use App\Domain\Oauth\Model\Token;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Infrastructure\Eloquent\Model\ClientDevice;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table((new Token())->getTable(), function (Blueprint $table) {
            if (!Schema::hasColumn((new Token())->getTable(), 'deviceId')) {
                $table
                    ->foreignId('deviceId')
                    ->nullable()
                    ->index('index' . ClientDevice::getTableName() . 'deviceId')
                    ->constrained(
                        ClientDevice::getTableName(),
                        ClientDevice::getPrimaryName()
                    )
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table((new Token())->getTable(), function (Blueprint $table) {
            if (Schema::hasColumn((new Token())->getTable(), 'deviceId')) {
                $table->dropForeign(['deviceId']);
                $table->dropColumn('deviceId');
            }
        });
    }
};
