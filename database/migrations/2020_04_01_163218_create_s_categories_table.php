<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('scategory_name');
            $table->string('scategory_detail')->nullable();
            $table->string('scategory_type');
            $table->decimal('scategory_price',16,0)->nullable();
            $table->boolean('scategory_active');
            $table->dateTime('scategory_created_at');
            $table->integer('scategory_created_by');
            $table->dateTime('scategory_modified_at')->nullable();
            $table->integer('scategory_modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_categories');
    }
}
