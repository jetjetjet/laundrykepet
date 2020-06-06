<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('loan_detail_loan_id');
            $table->integer('loan_detail_payment');
            $table->decimal('loan_detail_installment');
            $table->dateTime('loan_detail_date');
            
            $table->boolean('loan_detail_active');
            $table->dateTime('loan_detail_created_at');
            $table->integer('loan_detail_created_by');
            $table->dateTime('loan_detail_modified_at')->nullable();;
            $table->integer('loan_detail_modified_by')->nullable();;


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_details');
    }
}
