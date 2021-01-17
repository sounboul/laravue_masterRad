<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order_number');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')
                                        ->on('customers')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade');
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')
                                        ->on('articles')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade');
            $table->integer('amount');
            $table->integer('price');
            $table->integer('points');
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
        Schema::dropIfExists('orders');
    }
}
