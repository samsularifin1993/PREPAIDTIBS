<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionSuccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_successes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trans_id_merchant');
            $table->integer('channel')->unsigned();
            $table->integer('product')->unsigned();
            $table->string('nd');
            $table->integer('duration');
            $table->integer('price');
            $table->integer('ppn');
            $table->dateTime('payment_dtm');
            $table->dateTime('request_dtm');
            $table->dateTime('start_dtm');
            $table->dateTime('end_dtm');
            $table->integer('payment')->unsigned();
            $table->integer('org')->unsigned();
            $table->string('prov_status');
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
        Schema::dropIfExists('transaction_successes');
    }
}
