<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            // $table->bigIncrements('id');
            // $table->integer('order_detail_order_id');
            // $table->integer('order_detail_category_id');
            // $table->integer('order_detail_qty');
            // $table->decimal('order_detail_total',16,0);

            // $table->boolean('order_detail_active');
            // $table->dateTime('order_detail_created_at');
            // $table->integer('order_detail_created_by');
            // $table->dateTime('order_detail_modified_at')->nullable();
            // $table->integer('order_detail_modified_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
