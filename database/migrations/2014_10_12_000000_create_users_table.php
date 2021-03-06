<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable()->default('UnknownUser.png');
            $table->unsignedBigInteger('stores_id')->default('1');
            $table->foreign('stores_id')->references('id')
                                        ->on('stores')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade')
                                        ->nullable();
            $table->unsignedBigInteger('department_id')->default('1');
            $table->foreign('department_id')->references('id')
                                        ->on('department')
                                        ->onUpdate('cascade')
                                        ->onDelete('cascade');
            $table->string('active')->default('pending');
            $table->string('account')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
