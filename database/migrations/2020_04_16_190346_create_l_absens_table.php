<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLAbsensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labsen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('labsen_detail')->nullable();
            $table->boolean('labsen_active');
            $table->dateTime('labsen_created_at');
            $table->integer('labsen_created_by');
            $table->dateTime('labsen_modified_at')->nullable();
            $table->integer('labsen_modified_by')->nullable();
        });

        Schema::create('dabsen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dabsen_labsen_id');
            $table->integer('dabsen_employee_id');
            $table->string('dabsen_note')->nullable();
            $table->boolean('dabsen_active');
            $table->dateTime('dabsen_created_at');
            $table->integer('dabsen_created_by');
            $table->dateTime('dabsen_modified_at')->nullable();
            $table->integer('dabsen_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('l_absens');
    }
}
