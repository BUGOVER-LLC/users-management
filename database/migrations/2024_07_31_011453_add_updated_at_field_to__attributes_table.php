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
        Schema::table(\App\Domain\UMRA\Model\Attribute::getTableName(), function (Blueprint $table) {
            if (!Schema::hasColumn(\App\Domain\UMRA\Model\Attribute::getTableName(), 'updatedAt')) {
                $table->timestamp('updatedAt')->useCurrent()->after('attributeDescription');
            }
        });
    }
};
