<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSteamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('steam_customer_id');
            $table->string('steam_invoice')->unique();
            $table->dateTime('steam_date');
            $table->decimal('steam_total_price',16,2);
            $table->boolean('steam_active');
            $table->decimal('steam_paid',16,0)->nullable();
            $table->boolean('steam_paidoff')->nullable();

            $table->integer('steam_executed_by')->nullable();
            $table->dateTime('steam_executed_at')->nullable();

            $table->dateTime('steam_finished_at')->nullable();
            $table->integer('steam_finished_by')->nullable();

            $table->string('steam_taken_by')->nullable();
            $table->dateTime('steam_taken_at')->nullable();

            $table->dateTime('steam_created_at');
            $table->integer('steam_created_by');
            
            $table->dateTime('steam_modified_at')->nullable();
            $table->integer('steam_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steams');
    }
}
