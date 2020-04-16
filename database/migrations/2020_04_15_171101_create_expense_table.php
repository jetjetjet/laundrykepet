<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('expense_name');
            $table->string('expense_detail');
            $table->decimal('expense_price',16,0);
            $table->boolean('expense_active',);
            $table->dateTime('expense_created_at');
            $table->integer('expense_created_by');
            $table->dateTime('expense_modified_at')->nullable();
            $table->integer('expense_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense');
    }
}
