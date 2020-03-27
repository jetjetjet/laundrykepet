<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('employee_user_id')->nullable();
            $table->string('employee_name')->unique();
            $table->string('employee_contact')->nullable();
            $table->string('employee_address')->nullable();
            $table->decimal('employee_sallary',16,0)->nullable();
            $table->string('employee_type');
            
            $table->boolean('employee_active');
            $table->dateTime('employee_created_at');
            $table->integer('employee_created_by');
            $table->dateTime('employee_modified_at')->nullable();
            $table->integer('employee_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
