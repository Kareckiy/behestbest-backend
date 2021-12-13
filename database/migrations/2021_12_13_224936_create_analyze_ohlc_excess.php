<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyzeOhlcExcess extends Migration
{
    public function up()
    {
        Schema::create(
            'analyze_ohlc_excess',
            function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('analyze_ohlc_id');
                $table->foreign('analyze_ohlc_id')->references('id')->on('analyze_ohlc_types_dict');

                $table->float('excess');
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('analyze_ohlc_excess');
    }
}
