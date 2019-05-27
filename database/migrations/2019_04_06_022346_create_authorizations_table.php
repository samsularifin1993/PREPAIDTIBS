<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();

            $table->enum('role_r',['true','false']);
            $table->enum('role_i',['true','false']);
            $table->enum('role_u',['true','false']);
            $table->enum('role_d',['true','false']);

            $table->enum('user_r',['true','false']);
            $table->enum('user_i',['true','false']);
            $table->enum('user_u',['true','false']);
            $table->enum('user_d',['true','false']);

            $table->enum('channel_r',['true','false']);
            $table->enum('channel_i',['true','false']);
            $table->enum('channel_u',['true','false']);
            $table->enum('channel_d',['true','false']);

            $table->enum('organization_r',['true','false']);
            $table->enum('organization_i',['true','false']);
            $table->enum('organization_u',['true','false']);
            $table->enum('organization_d',['true','false']);

            $table->enum('payment_r',['true','false']);
            $table->enum('payment_i',['true','false']);
            $table->enum('payment_u',['true','false']);
            $table->enum('payment_d',['true','false']);

            $table->enum('product_family_r',['true','false']);
            $table->enum('product_family_i',['true','false']);
            $table->enum('product_family_u',['true','false']);
            $table->enum('product_family_d',['true','false']);

            $table->enum('product_r',['true','false']);
            $table->enum('product_i',['true','false']);
            $table->enum('product_u',['true','false']);
            $table->enum('product_d',['true','false']);

            $table->enum('v_dashboard_admin',['true','false']);
            $table->enum('v_dashboard_revenue',['true','false']);
            $table->enum('r_trx_success',['true','false']);
            $table->enum('r_trx_reject',['true','false']);
            $table->enum('r_revenue',['true','false']);

            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('authorizations');
    }
}
