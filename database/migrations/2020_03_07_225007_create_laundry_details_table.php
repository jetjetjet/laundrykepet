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
