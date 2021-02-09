<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierIdToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable()->after('price');
            $table->foreign('supplier_id')->references('id')->on('suppliers')
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
        Schema::table('articles', function (Blueprint $table) {
            
            // 1. Drop foreign key constraints
            $table->dropForeign(['supplier']);

            // 2. Drop the column
            $table->dropColumn('supplier_id');
        });
    }
}
