<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_stores', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')
                                        ->on('department')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade');
            $table->unsignedBigInteger('stores_id');
            $table->foreign('stores_id')->references('id')
                                        ->on('stores')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_stores');
    }
}
