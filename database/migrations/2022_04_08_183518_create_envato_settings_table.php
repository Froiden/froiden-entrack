<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvatoSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envato_settings', function (Blueprint $table) {
            $table->id();
            $table->string('envato_username')->nullable();
            $table->unsignedMediumInteger('sales')->default(0);
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->string('followers')->nullable();
            $table->string('envato_api_key')->nullable();
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->string('country')->nullable();
            $table->string('balance')->nullable();
            $table->string('timezone')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('daily_email')->default(0);
            $table->dateTime('last_cron_run')->nullable();
            $table->boolean('hide_cron_message')->default(0);
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
        Schema::dropIfExists('envato_settings');
    }

}
