<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('setting_category',25);
            $table->string('setting_key',200);
            $table->string('setting_value',200);
            $table->boolean('setting_active',);
            $table->dateTime('setting_created_at');
            $table->integer('setting_created_by');
            $table->dateTime('setting_modified_at')->nullable();
            $table->integer('setting_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}