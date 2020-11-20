<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToClassifiedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classifieds', function (Blueprint $table) {
            $table->string('title',155);
            $table->string('location',155)->nullable();
            $table->string('region',155)->nullable();
            $table->string('description',155);
            $table->string('image',155);
            $table->string('price',155);
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classifieds', function (Blueprint $table) {
            //
        });
    }
}
