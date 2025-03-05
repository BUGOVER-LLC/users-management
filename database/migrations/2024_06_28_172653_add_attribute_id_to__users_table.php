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
    public function up()
    {
        Schema::table(User::getTableName(), function (Blueprint $table) {
            if (!Schema::hasColumn(User::getTableName(), 'attributeId')) {
                $table
                    ->foreignId('attributeId')
                    ->nullable()
                    ->after('roleId')
                    ->constrained(
                        \App\Domain\UMRA\Model\Attribute::getTableName(),
                        \App\Domain\UMRA\Model\Attribute::getPrimaryName()
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
        Schema::table('_users', function (Blueprint $table) {
        });
    }
};
