<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_colors', function (Blueprint $table) {
            $table->unsignedBigInteger('articles_id');
            $table->foreign('articles_id')->references('id')->on('articles');
            $table->unsignedBigInteger('colors_id');
            $table->foreign('colors_id')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_colors');
    }
}
