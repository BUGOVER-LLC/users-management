<?php

declare(strict_types=1);

use App\Core\Enum\DocumentType;
use App\Core\Enum\PersonType;
use App\Domain\CUM\Model\Citizen;
use Illuminate\Database\Migrations\Migration;
use Infrastructure\Illuminate\Database\Schema;
use Infrastructure\Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Citizen::getTableName(), function (Blueprint $table) {
            $table->increments(Citizen::getPrimaryName());
            $table->char('uuid', 36);
            $table->foreignId('userId')
                ->nullable()
                ->constrained('Users', 'userId')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('profileId')
                ->nullable()
                ->constrained('Profiles', 'profileId')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('password')->nullable();
            $table->enum(
                column: 'personType',
                allowed: PersonType::toArray(),
            )->nullable();

            $table->enum(
                column: 'documentType',
                allowed: DocumentType::toArray(),
            )->nullable();

            $table->string('documentValue', 200)
                ->nullable()
                ->unique();
            $table->json('documentFile')->nullable();
            $table->boolean('isActive')->default(false);
            $table->boolean('isChecked')->default(false);
            $table->dateTime('lastActivityAt')->nullable();
            $table->dateTime('lastDeactivateAt')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Citizen::getTableName());
    }
};
