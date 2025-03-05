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
    public function up(): void
    {
        Schema::create('InvitationCitizens', function (Blueprint $table) {
            $table->increments('invitationCitizenId');
            $table->unsignedInteger('citizenId')->nullable();
            $table->string('inviteUrl')->unique();
            $table->string('inviteEmail')->unique();
            $table->char('inviteToken', 128)->unique();
            $table->dateTime('passed');
            $table->dateTime('deletedAt')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->foreign(['citizenId'], 'foreign_invitation_citizens_citizenId')->references(['citizenId'])->on(
                'Citizens'
            )->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('InvitationCitizens');
    }
};
