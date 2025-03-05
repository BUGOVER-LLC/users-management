<?php

declare(strict_types=1);

use App\Core\Enum\Gender;
use App\Domain\UMAC\Model\Profile;
use Illuminate\Database\Migrations\Migration;
use Infrastructure\Illuminate\Database\Schema;
use Infrastructure\Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Profile::getTableName(), function (Blueprint $table) {
            $table->increments(Profile::getPrimaryName());
            $table->char('psn', 10)->nullable();
            $table->string('firstName', 120);
            $table->string('lastName', 120);
            $table->string('patronymic', 120)->nullable();
            $table->date('dateBirth')->nullable();
            $table->enum('gender', Gender::toArray())->nullable();
            $table->text('avatar')->nullable();

            $table->string("currentLoginType")->nullable();
            $table->unsignedBigInteger("currentLoginId")->nullable();
            $table->index(["currentLoginType", "currentLoginId"]);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Profile::getTableName());
    }
};
