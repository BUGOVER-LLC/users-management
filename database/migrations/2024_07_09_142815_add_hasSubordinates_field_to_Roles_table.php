<?php

declare(strict_types=1);

use App\Domain\UMRP\Model\Role;
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
        Schema::table(Role::getTableName(), function (Blueprint $table) {
            if (!Schema::hasColumn(Role::getTableName(), 'hasSubordinates')) {
                $table->boolean('hasSubordinates')->after('roleActive')->default(false);
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
        Schema::table(Role::getTableName(), function (Blueprint $table) {
            if (Schema::hasColumn(Role::getTableName(),'hasSubordinates')) {
                $table->dropColumn('hasSubordinates');
            }
        });
    }
};
