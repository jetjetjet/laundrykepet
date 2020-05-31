<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('loan_employee_id');
            $table->string('loan_detail');
            $table->decimal('loan_amount',16,0);
            $table->integer('loan_tenor');
            $table->boolean('loan_paidoff');
            $table->boolean('loan_active');
            $table->dateTime('loan_created_at');
            $table->integer('loan_created_by');
            $table->dateTime('loan_modified_at')->nullable();;
            $table->integer('loan_modified_by')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
