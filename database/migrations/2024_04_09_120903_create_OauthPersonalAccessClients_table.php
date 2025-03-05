<?php

declare(strict_types=1);

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
        Schema::create('OauthPersonalAccessClients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OauthPersonalAccessClients');
    }
};
