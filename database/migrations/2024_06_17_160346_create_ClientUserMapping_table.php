<?php

declare(strict_types=1);

use App\Domain\System\Model\System;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Infrastructure\Eloquent\Model\ClientUserMapping;
use Infrastructure\Illuminate\Database\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(ClientUserMapping::getTableName(), function (Schema\Blueprint $table) {
            $table->increments('clientUserMappingId');
            $table
                ->foreignId('systemId')
                ->index('index'.ClientUserMapping::getTableName().'clientId')
                ->constrained(
                    System::getTableName(),
                    System::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('userId')->index('index'.ClientUserMapping::getTableName().'userId');
            $table->string('userType', 50)->index('index'.ClientUserMapping::getTableName().'userType');

            $table->softDeletes();
            $table->timestamp('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(ClientUserMapping::getTableName());
    }
};
