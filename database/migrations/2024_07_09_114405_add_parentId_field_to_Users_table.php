<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\User;
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
        Schema::table(User::getTableName(), function (Blueprint $table) {
            if (!Schema::hasColumn(User::getTableName(), 'parentId')) {
                $table
                    ->foreignId('parentId')
                    ->nullable()
                    ->after('attributeId')
                    ->constrained(
                        User::getTableName(),
                        User::getPrimaryName()
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
    public function down(): void
    {
        Schema::table(User::getTableName(), function (Blueprint $table) {
            //
        });
    }
};
