<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\Profile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Profile::getTableName(), function (Blueprint $table) {
            if (! Schema::hasColumns(Profile::getTableName(), ['currentLoginType', 'currentLoginId'])) {
                $table->string("currentLoginType")->nullable()->after('avatar');
                $table->unsignedBigInteger("currentLoginId")->nullable()->after('currentLoginType');
                $table->index(["currentLoginType", "currentLoginId"]);
            }
        });
    }
};
