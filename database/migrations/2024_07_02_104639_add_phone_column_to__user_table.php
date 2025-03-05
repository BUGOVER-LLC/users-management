<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table(User::getTableName(), function (Blueprint $table) {
            if (! Schema::hasColumn(User::getTableName(), 'phone')) {
                $table->string('phone', 50)->nullable()->after('email');
            }
        });
    }
};
