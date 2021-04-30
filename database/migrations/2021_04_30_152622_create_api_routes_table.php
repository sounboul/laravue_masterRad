<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_name_id');
            $table->foreign('route_name_id')->references('id')
                                        ->on('route_name')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade')
                                        ->nullable();
            $table->string('name');
            $table->integer('route_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_routes');
    }
}
