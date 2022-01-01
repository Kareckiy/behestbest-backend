<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertRowInAnayzeOhlcTypesDict extends Migration
{
    public function up()
    {
        DB::table('analyze_ohlc_types_dict')->insert(
            [
                'title' => 'Сравнение volume с медианой за неделю',
                'slug' => 'compare_volume_with_week_median',
                'created_at' => now()
            ]
        );

        DB::table('analyze_ohlc_types_dict')->insert(
            [
                'title' => 'Сравнение volume с медианой за месяц',
                'slug' => 'compare_volume_with_month_median',
                'created_at' => now()
            ]
        );
    }

    public function down()
    {
        DB::table('analyze_ohlc_types_dict')
            ->where('slug', 'compare_volume_with_week_median')
            ->delete();

        DB::table('analyze_ohlc_types_dict')
            ->where('slug', 'compare_volume_with_month_median')
            ->delete();
    }
}
