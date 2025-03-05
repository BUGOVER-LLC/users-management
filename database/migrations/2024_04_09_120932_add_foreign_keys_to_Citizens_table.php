<?php

declare(strict_types=1);

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
        Schema::table('Citizens', function (Blueprint $table) {
            $table->foreign(['profileId'], 'foreign_citizens_profiles_profile_id')->references(['profileId'])->on(
                'Profiles'
            )->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['userId'], 'foreign_citizens_users_user_id')->references(['userId'])->on(
                'Users'
            )->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Citizens', function (Blueprint $table) {
            $table->dropForeign('foreign_citizens_profiles_profile_id');
            $table->dropForeign('foreign_citizens_users_user_id');
        });
    }
};
