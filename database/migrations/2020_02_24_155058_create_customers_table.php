<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name');
            $table->text('customer_address');
            $table->string('customer_phone');
            $table->boolean('customer_active');
            $table->dateTime('customer_created_at');
            $table->integer('customer_created_by');
            $table->dateTime('customer_modified_at')->nullable();;
            $table->integer('customer_modified_by')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
