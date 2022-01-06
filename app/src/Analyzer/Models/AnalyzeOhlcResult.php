<?php

declare(strict_types=1);

namespace App\src\Analyzer\Models;

use App\Models\Ohlc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnalyzeOhlcResult extends Model
{
    protected $table = 'analyze_ohlc_results';

    public $timestamps = false;

    protected $fillable = [
        'ohlc_id',
        'analyze_ohlc_type_id',
        'excess_percent',
        'excess_absolute',
        'created_at',
    ];

    public function ohlc(): BelongsTo
    {
        return $this->belongsTo(Ohlc::class, 'ohlc_id');
    }

    public function analyzeType(): HasOne
    {
        return $this->hasOne(AnalyzeOhlcTypesDict::class, 'id');
    }

    public function getExcessPercent(): float
    {
        return $this->excess_percent;
    }

    public function getExcessAbsolute(): float
    {
        return $this->excess_absolute;
    }
}
