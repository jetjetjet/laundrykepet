<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role_name');
            $table->string('role_detail')->nullable();
            $table->text('role_permissions');

            $table->boolean('role_active');
            $table->dateTime('role_created_at');
            $table->integer('role_created_by');
            $table->dateTime('role_modified_at')->nullable();
            $table->integer('role_modified_by')->nullable();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_role_user_id');
            $table->integer('user_role_role_id');

            $table->boolean('user_role_active');
            $table->dateTime('user_role_created_at');
            $table->integer('user_role_created_by');
            $table->dateTime('user_role_modified_at')->nullable();
            $table->integer('user_role_modified_by')->nullable();
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->integer('user_role_user_id')->unsigned()->change();
            $table->foreign('user_role_user_id')->references('id')->on('users');

            $table->integer('user_role_role_id')->unsigned()->change();
            $table->foreign('user_role_role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
