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
            $table->string('lctypes_name',50);
            $table->boolean('lctypes_active');
            $table->dateTime('lctypes_created_at');
            $table->integer('lctypes_created_by');
            $table->dateTime('lctypes_modified_at')->nullable();
            $table->integer('lctypes_modified_by')->nullable();
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
