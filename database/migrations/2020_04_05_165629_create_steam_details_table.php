<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSteamDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sdetail_steam_id');
            $table->integer('sdetail_scategory_id');
            $table->string('sdetail_plate',10);            
            $table->decimal('sdetail_price',16,2);
            $table->integer('sdetail_qty');
            $table->boolean('sdetail_active');

            $table->dateTime('sdetail_created_at');
            $table->integer('sdetail_created_by');
            $table->dateTime('sdetail_modified_at')->nullable();
            $table->integer('sdetail_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steam_details');
    }
}
