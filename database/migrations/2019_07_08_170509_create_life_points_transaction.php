<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLifePointsTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('life_points_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transaction_point');
            $table->tinyInteger('point_operator');
            $table->tinyInteger('status');
            $table->bigInteger('transaction_id');
            $table->unsignedBigInteger('life_point_id')->unsigned();
            $table->unsignedInteger('point_transaction_type_id')->unsigned();
            $table->foreign('life_point_id')->references('id')->on('life_points');
            $table->foreign('point_transaction_type_id')->references('id')->on('life_points_transaction_type');

            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::drop('life_points_transactions');
    }
}

