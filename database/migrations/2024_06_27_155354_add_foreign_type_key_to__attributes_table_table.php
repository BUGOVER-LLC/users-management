<?php

declare(strict_types=1);

use App\Domain\UMRA\Model\Resource;
use Illuminate\Database\Migrations\Migration;
use Infrastructure\Illuminate\Database\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(\App\Domain\UMRA\Model\Attribute::getTableName(), function (Schema\Blueprint $table) {
            if (!Schema::hasColumn(\App\Domain\UMRA\Model\Attribute::getTableName(), 'resourceId')) {
                $table
                    ->foreignId('resourceId')
                    ->index('index'.Resource::getTableName().'resourceId')
                    ->nullable()
                    ->constrained(
                        Resource::getTableName(),
                        Resource::getPrimaryName()
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
        Schema::table(\App\Domain\UMRA\Model\Attribute::getTableName(), function (Schema\Blueprint $table) {
            if (Schema::hasColumn(\App\Domain\UMRA\Model\Attribute::getTableName(), 'resourceId')) {
                $table->dropForeign('index'.Resource::getTableName().'resourceId');
            }
        });
    }
};
