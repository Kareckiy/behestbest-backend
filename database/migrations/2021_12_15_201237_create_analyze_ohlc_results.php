<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyzeOhlcResults extends Migration
{
    public function up()
    {
        Schema::create(
            'analyze_ohlc_results',
            function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('ohlc_id');
                $table->foreign('ohlc_id')->references('id')->on('ohlc');

                $table->unsignedTinyInteger('analyze_ohlc_type_id');
                $table->foreign('analyze_ohlc_type_id')->references('id')->on('analyze_ohlc_types_dict');

                $table->float('excess_percent')->nullable();
                $table->float('excess_absolute')->nullable();

                $table->timestamp('created_at');
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('analyze_ohlc_results');
    }
}
