<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\Profile;
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
        Schema::table(Profile::getTableName(), function (Blueprint $table) {
            $table->text('avatar')->nullable()->change();
        });
    }
};
