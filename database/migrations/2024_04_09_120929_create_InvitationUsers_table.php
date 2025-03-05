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
        Schema::create('InvitationUsers', function (Blueprint $table) {
            $table->increments('invitationUserId');
            $table->unsignedInteger('userId')->index('foreign_invitation_users_user_id');
            $table->string('inviteUrl', 500)->unique();
            $table->char('inviteToken', 128)->unique();
            $table->dateTime('passed');
            $table->json('psnInfo')->nullable();
            $table->dateTime('acceptedAt')->nullable();
            $table->dateTime('deletedAt')->nullable();
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
        Schema::dropIfExists('InvitationUsers');
    }
};
