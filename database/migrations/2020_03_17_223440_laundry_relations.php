<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaundryRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laundries', function (Blueprint $table) {
            $table->integer('laundry_customer_id')->unsigned()->change();
            $table->foreign('laundry_customer_id')->references('id')->on('customers');

            $table->integer('laundry_created_by')->unsigned()->change();
            $table->foreign('laundry_created_by')->references('id')->on('users');
        });

        Schema::table('ldetails', function (Blueprint $table) {
            $table->integer('ldetail_laundry_id')->unsigned()->change();
            $table->foreign('ldetail_laundry_id')->references('id')->on('laundries');

            $table->integer('ldetail_lcategory_id')->unsigned()->change();
            $table->foreign('ldetail_lcategory_id')->references('id')->on('lcategories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
