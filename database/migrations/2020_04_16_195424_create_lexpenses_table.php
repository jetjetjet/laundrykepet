<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLexpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lexpenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lexpenses_name');
            $table->string('lexpenses_detail');
            $table->decimal('lexpenses_price',16,2);
            $table->boolean('lexpenses_active',);
            $table->dateTime('lexpenses_created_at');
            $table->integer('lexpenses_created_by');
            $table->dateTime('lexpenses_modified_at')->nullable();
            $table->integer('lexpenses_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lexpenses');
    }
}
