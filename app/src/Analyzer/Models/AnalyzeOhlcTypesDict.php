<?php

declare(strict_types=1);

namespace App\src\Analyzer\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyzeOhlcTypesDict extends Model
{
    protected $table = 'analyze_ohlc_types_dict';

    public $timestamps = false;

    public const ANALYZE_TYPE_WEEK_MEDIAN_VALUE_ID = 1;
    public const ANALYZE_TYPE_MONTH_MEDIAN_VALUE_ID = 2;

    protected $fillable = [
        'title',
        'slug',
        'created_at',
    ];
}
