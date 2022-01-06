<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertAnalyzeOhlcResults extends Migration
{
    public function up()
    {
        DB::statement("INSERT INTO analyze_ohlc_results
(ohlc_id, analyze_ohlc_type_id, excess_percent, excess_absolute, created_at)
SELECT  id, 1, null, null, (NOW() + INTERVAL 3 HOUR)
From ohlc;");
    }

    public function down()
    {
        //
    }
}
