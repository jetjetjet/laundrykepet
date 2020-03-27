<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->integer('role_id');
            $table->string('user_name',50)->unique();
            $table->string('user_full_name', 100)->nullable();
            $table->string('user_password', 100);
            $table->string('user_phone', 25)->nullable();
            $table->string('user_address', 200)->nullable();
            $table->boolean('user_active');
            $table->dateTime('user_created_at');
            $table->integer('user_created_by');
            $table->dateTime('user_modified_at')->nullable();
            $table->integer('user_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
