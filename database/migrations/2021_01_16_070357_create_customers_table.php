<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->date('dob')->nullable();
            $table->integer('ID_number')->nullable()->unique();
            $table->string('street')->nullable();
            $table->integer('number')->nullable();
            $table->string('city')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->date('member_since');
            $table->integer('order_id')->nullable();
            $table->integer('total_points')->default(0);
            $table->string('level')->nullable();
            $table->string('active')->default('active');
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
        Schema::dropIfExists('customers');
    }
}
