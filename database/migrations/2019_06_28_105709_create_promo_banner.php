<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_banner', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('promo_id')->unsigned();
            $table->integer('banner_id')->unsigned();
            $table->foreign('promo_id')->references('id')->on('promo');
            $table->foreign('banner_id')->references('id')->on('banners');
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
        Schema::drop('promo_banner');
    }
}
