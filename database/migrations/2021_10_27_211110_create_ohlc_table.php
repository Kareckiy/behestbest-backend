<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOhlcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ohlc', function (Blueprint $table) {
            $table->id();

            $table->timestamp('time');

            $table->float('open_price');
            $table->float('close_price');

            $table->float('high_price');
            $table->float('low_price');

            $table->float('vwap'); // средняя цена по объему
            $table->float('volume'); // объем торговли

            $table->float('deals_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ohlc');
    }
}
