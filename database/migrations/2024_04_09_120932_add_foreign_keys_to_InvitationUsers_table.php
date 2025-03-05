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
        Schema::table('InvitationUsers', function (Blueprint $table) {
            $table
                ->foreign(['userId'], 'foreign_invitation_users_user_id')
                ->references(['userId'])
                ->on('Users')
                ->onUpdate('no action')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('InvitationUsers', function (Blueprint $table) {
            $table->dropForeign('foreign_invitation_users_user_id');
        });
    }
};
