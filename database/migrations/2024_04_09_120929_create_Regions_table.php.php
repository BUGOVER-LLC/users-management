<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Regions', function (Blueprint $table) {
            $table->increments('regionId');
            $table->string('code', 10);
            $table->string('name', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Regions');
    }
};
