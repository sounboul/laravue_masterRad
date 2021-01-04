<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_stores', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_stores');
    }
}
