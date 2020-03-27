<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('orders', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('order_invoice')->unique();
        //     $table->integer('order_customer_id');
        //     $table->integer('order_user_id');
        //     $table->integer('order_est_days');
        //     $table->dateTime('order_est_date');
        //     $table->decimal('order_paid',16,0)->nullable();
        //     $table->boolean('order_delivery')->nullable();

        //     $table->dateTime('order_executed_at')->nullable();
        //     $table->integer('order_executed_by')->nullable();

        //     $table->dateTime('order_settled_at')->nullable();
        //     $table->integer('order_settled_by')->nullable();

        //     $table->dateTime('order_finished_at')->nullable();
        //     $table->integer('order_finished_by')->nullable();

        //     $table->dateTime('order_delivered_by')->nullable();
        //     $table->integer('order_delivered_at')->nullable();

        //     $table->boolean('order_active');
        //     $table->dateTime('order_created_at');
        //     $table->integer('order_created_by');
        //     $table->dateTime('order_modified_at')->nullable();;
        //     $table->integer('order_modified_by')->nullable();;
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
