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
            $table->unsignedBigInteger('articles_id');
            $table->foreign('articles_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('stores_id');
            $table->foreign('stores_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
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
