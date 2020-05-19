<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ldetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ldetail_laundry_id');
            $table->integer('ldetail_lcategory_id');
            $table->integer('ldetail_qty');
            $table->decimal('ldetail_total',16,0);
            $table->string('ldetail_condition')->nullable();
            $table->string('ldetail_detail')->nullable();
            $table->dateTime('ldetail_start_date');
            $table->dateTime('ldetail_end_date');

            $table->dateTime('ldetail_executed_at')->nullable();
            $table->integer('ldetail_executed_by')->nullable();
            $table->dateTime('ldetail_finished_at')->nullable();
            $table->integer('ldetail_finished_by')->nullable();
            $table->integer('ldetail_delivered_by')->nullable();
            $table->dateTime('ldetail_delivered_at')->nullable();
            $table->string('ldetail_taken_by')->nullable();
            $table->dateTime('ldetail_taken_at')->nullable();

            $table->boolean('ldetail_active');
            $table->dateTime('ldetail_created_at');
            $table->integer('ldetail_created_by');
            $table->dateTime('ldetail_modified_at')->nullable();
            $table->integer('ldetail_modified_by')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ldetail_s');
    }
}
