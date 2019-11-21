<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title',255)->change();;
            $table->text("description")->change();;
            $table->string('image_path',255)->change();;
            $table->string('link')->nullable()->change();;
            // $table->integer('status')->default(1);
            // $table->integer('promo_id');
            // $table->foreign('promo_id')->references('id')->on('promo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');

    }
}
