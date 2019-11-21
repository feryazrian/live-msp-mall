<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePpobPromo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppob_promo', function (Blueprint $table) {
            $table->dropColumn(['name', 'type', 'code_promo']);
            $table->unsignedBigInteger('promo_id');
            $table->unsignedBigInteger('ppob_type_id');
            $table->foreign('promo_id')->references('id')->on('promo');
            $table->foreign('ppob_type_id')->references('id')->on('ppob_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ppob_promo', function (Blueprint $table) {
            //
        });
    }
}
