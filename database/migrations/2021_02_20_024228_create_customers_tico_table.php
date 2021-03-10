<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_tico', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('order_id')->default(0);
            $table->integer('category_id')->nullable();
            $table->integer('total_points');
            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone');
            $table->string('name')->nullable();
            $table->timestamps('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_tico');
    }
}
