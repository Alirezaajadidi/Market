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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table) {
            $table->unsignedInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->primary(['article_id', 'category_id']);
        });


    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('categories');
    }
}
