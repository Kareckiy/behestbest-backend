<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOhlcTable extends Migration
{
    public function up()
    {
        Schema::create('ohlc', function (Blueprint $table) {
            $table->id();

            $table->string('altname');

            $table->string('open_price');
            $table->string('close_price');

            $table->string('high_price');
            $table->string('low_price');

            $table->string('vwap'); // средняя цена по объему
            $table->string('volume'); // объем торговли

            $table->integer('deals_count');

            $table->string('time');

            $table->timestamp('created_at');

            $table->unique(['altname', 'time']);
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
