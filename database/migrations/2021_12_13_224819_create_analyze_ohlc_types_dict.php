<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyzeOhlcTypesDict extends Migration
{
    public function up()
    {
        Schema::create(
            'analyze_ohlc_types_dict',
            function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->string('title');
                $table->string('slug')->unique();

                $table->timestamp('created_at');
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('analyze_ohlc_types_dict');
    }
}
