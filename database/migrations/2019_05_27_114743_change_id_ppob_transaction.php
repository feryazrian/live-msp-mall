<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdPpobTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppob_transactions', function (Blueprint $table) {
            $table->uuid('reff_id');
            $table->renameColumn('hp','cust_number');
            $table->string('serial_number')->nullable();
            $table->renameColumn('pulsa_code','tr_code');
            $table->double('balance',35,5);
            $table->string('r_balance')->nullable();
            $table->string('pin')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ppob_transactions', function (Blueprint $table) {
            //
        });
    }
}
