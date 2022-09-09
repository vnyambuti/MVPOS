<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColoursProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colours_products', function (Blueprint $table) {
            $table->unsignedBigInteger('colours_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('colours_id')->references('id')->on('colours')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colours_products', function (Blueprint $table) {
            //
        });
    }
}
