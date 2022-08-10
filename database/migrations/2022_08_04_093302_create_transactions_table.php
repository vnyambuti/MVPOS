<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // 'o_id','total','p_id','receipt'
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('total');
            $table->string('receipt')->nullable()->default('');
            $table->unsignedBigInteger('o_id')->nullable();
            $table->foreign('o_id')->references('id')->on('orders')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('p_id')->nullable();
            $table->foreign('p_id')->references('id')->on('payments')->onDelete('set null')->onUpdate('cascade');
            $table->string('status')->default('processing')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
