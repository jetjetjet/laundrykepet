<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lcategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lcategory_name');
            $table->string('lcategory_detail')->nullable();
            $table->string('lcategory_days')->nullable();
            $table->decimal('lcategory_price',16,0);

            $table->boolean('lcategory_active');
            $table->dateTime('lcategory_created_at');
            $table->integer('lcategory_created_by');
            $table->dateTime('lcategory_modified_at')->nullable();
            $table->integer('lcategory_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('l_categories');
    }
}
