<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberLevel', function (Blueprint $table) {
            $table->id();
            $table->integer('regular')->default(0);
            $table->integer('silver')->default(0);
            $table->integer('gold')->default(0);
            $table->integer('premium')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberLevel');
    }
}
