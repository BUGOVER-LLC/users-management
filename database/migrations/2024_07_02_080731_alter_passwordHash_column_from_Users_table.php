<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(User::getTableName(), function (Blueprint $table) {
            if (Schema::hasColumn(User::getTableName(), 'passwordHash')) {
                $table->renameColumn('passwordHash', 'password');
            }
        });
    }
};
