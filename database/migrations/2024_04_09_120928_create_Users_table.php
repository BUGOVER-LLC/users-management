<?php

declare(strict_types=1);

use App\Domain\UMAC\Model\User;
use App\Domain\UMRP\Model\Role;
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
        Schema::create(User::getTableName(), function (Blueprint $table) {
            $table->comment('Main users when has roles, attributes ex. (Court,...)');
            $table->increments('userId');
            $table->unsignedInteger('profileId')->nullable()->index('foreign_profiles_profile_id');
            $table
                ->foreignId('roleId')
                ->constrained(Role::getTableName(), Role::getPrimaryName())
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignId('attributeId')
                ->nullable()
                ->constrained(
                    \App\Domain\UMRA\Model\Attribute::getTableName(),
                    \App\Domain\UMRA\Model\Attribute::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignId('parentId')
                ->nullable()
                ->constrained(
                    User::getTableName(),
                    User::getPrimaryName()
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('phone', 50)->nullable();
            $table->string('password')->nullable();
            $table->boolean('active')->default(true);
            $table->dateTime('deletedAt')->nullable();
            $table->timestamp('updatedAt')->useCurrent();
            $table->timestamp('createdAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Users');
    }
};
