<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laundries', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('laundry_invoice')->unique();
            $table->integer('laundry_customer_id');
            $table->integer('laundry_agen_id')->nullable();
            //$table->integer('laundry_est_days');
            //$table->dateTime('laundry_est_date');
            $table->decimal('laundry_paid',16,0)->nullable();
            $table->boolean('laundry_paidoff')->nullable();
            $table->boolean('laundry_delivery')->nullable();

            $table->boolean('laundry_finished')->nullable();
            $table->boolean('laundry_completed')->nullable();

            // $table->dateTime('laundry_executed_at')->nullable();
            // $table->integer('laundry_executed_by')->nullable();

            // $table->dateTime('laundry_settled_at')->nullable();
            // $table->integer('laundry_settled_by')->nullable();

            // $table->dateTime('laundry_finished_at')->nullable();
            // $table->integer('laundry_finished_by')->nullable();

            // $table->integer('laundry_delivered_by')->nullable();
            // $table->dateTime('laundry_delivered_at')->nullable();
            // $table->string('laundry_taken_by')->nullable();
            // $table->dateTime('laundry_taken_at')->nullable();

            $table->boolean('laundry_active');
            $table->dateTime('laundry_created_at');
            $table->integer('laundry_created_by');
            $table->dateTime('laundry_modified_at')->nullable();;
            $table->integer('laundry_modified_by')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laundries');
    }
}
