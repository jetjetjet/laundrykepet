<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('categories', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('category_name');
        //     $table->string('category_detail')->nullable();;
        //     $table->decimal('category_price',16,0);
        //     $table->boolean('category_active');
        //     $table->dateTime('category_created_at');
        //     $table->integer('category_created_by');
        //     $table->dateTime('category_modified_at')->nullable();;
        //     $table->integer('category_modified_by')->nullable();;
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
