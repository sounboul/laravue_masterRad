<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleHistoryDiscountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_history_discount', function (Blueprint $table) {
            $table->id();
            $table->integer('customer')->nullable();
            $table->integer('article');
            $table->integer('price');
            $table->integer('bill')->nullable();
            $table->integer('earned_points')->nullable();
            $table->integer('order_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_history_discount');
    }
}
