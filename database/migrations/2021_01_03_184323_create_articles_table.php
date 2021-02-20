<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('articles')){
            Schema::create('articles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('code')->unique();
                $table->string('title');
                $table->unsignedBigInteger('categories_id');
                $table->foreign('categories_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('price');
                $table->integer('amount');
                $table->integer('discount')->default(0);/*
                $table->integer('discount_silver')->default(0);
                $table->integer('discount_gold')->default(0);
                $table->integer('discount_premium')->default(0);*/
                $table->string('image')->nullable();
                $table->string('brand')->nullable();
                $table->integer('rating')->nullable();
                $table->text('tags')->nullable();
                $table->longText('description')->nullable();
                $table->text('short_description')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
