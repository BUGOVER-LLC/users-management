<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Infrastructure\Eloquent\Model\ClientUserMapping;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(ClientUserMapping::getTableName(), function (Blueprint $table) {
            if (!Schema::hasColumn(ClientUserMapping::getTableName(), 'deletedAt')) {
                $table->softDeletes()->after('userType');
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
        Schema::table('_client_user_mapping', function (Blueprint $table) {
            //
        });
    }
};
