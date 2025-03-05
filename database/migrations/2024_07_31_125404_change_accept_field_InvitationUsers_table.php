<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\InvitationUser;
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
        Schema::table(InvitationUser::getTableName(), function (Blueprint $table) {
            if (!Schema::hasColumn(InvitationUser::getTableName(), 'acceptedAt')) {
                $table->dateTime('acceptedAt')->after('psnInfo')->nullable();
            }

            if (Schema::hasColumn(InvitationUser::getTableName(), 'accept')) {
                $table->dropColumn('accept');
            }
        });
    }
};
