<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLctypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lctypes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lctype_name',50);
            $table->boolean('lctype_active');
            $table->dateTime('lctype_created_at');
            $table->integer('lctype_created_by');
            $table->dateTime('lctype_modified_at')->nullable();
            $table->integer('lctype_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lctypes');
    }
}
