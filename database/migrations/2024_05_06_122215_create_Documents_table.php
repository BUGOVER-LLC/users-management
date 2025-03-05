<?php

declare(strict_types=1);

use App\Core\Enum\DocumentStatus;
use App\Core\Enum\DocumentType;
use Illuminate\Database\Migrations\Migration;
use Infrastructure\Illuminate\Database\Schema;
use Infrastructure\Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Documents', function (Blueprint $table) {
            $table->increments('documentId');
            $table->foreignId('citizenId')
                ->constrained('Citizens', 'citizenId')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->enum('documentType', DocumentType::toArray());
            $table->enum('documentStatus', DocumentStatus::toArray());
            $table->string('serialNumber')->unique();
            $table->string('citizenship', 3);
            $table->date('dateIssue');
            $table->date('dateExpiry')->nullable();
            $table->string('authority', 3);
            $table->json('photo')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Documents');
    }
};
