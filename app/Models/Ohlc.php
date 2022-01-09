<?php

declare(strict_types=1);

namespace App\Models;

use App\src\Analyzer\Models\AnalyzeOhlcResult;
use App\src\Stocks\Kraken\Dto\OhlcPerMoment;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Ohlc extends Model
{
    protected $table = 'ohlc';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'altname',
        'open_price',
        'close_price',
        'high_price',
        'low_price',
        'vwap',
        'volume',
        'deals_count',
        'time',
        'created_at'
    ];

    public function analyzeOhlcResults(): HasMany
    {
        return $this->hasMany(AnalyzeOhlcResult::class);
    }

    public static function createFromOhlcPerMoment(
        OhlcPerMoment $ohlcPerMoment,
        Pair $pair
    ): self
    {
        return self::create(
            [
                'altname' => $pair->getAltname(),
                'time' => $ohlcPerMoment->getServerTime(),
                'open_price' => $ohlcPerMoment->getPriceOpen(),
                'close_price' => $ohlcPerMoment->getPriceClose(),
                'high_price' => $ohlcPerMoment->getPriceHigh(),
                'low_price' => $ohlcPerMoment->getPriceLow(),
                'vwap' => $ohlcPerMoment->getVwap(),
                'volume' => $ohlcPerMoment->getVolume(),
                'deals_count' => $ohlcPerMoment->getCount(),
                'created_at' => now()
            ]
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAltname(): string
    {
        return $this->altname;
    }

    public function getVolume(): string
    {
        return $this->volume;
    }

    public function getTime(): DateTime
    {
        return Carbon::createFromTimestamp($this->time);
    }

    public function getOpenPrice(): string
    {
        return $this->open_price;
    }

    public function getClosePrice(): string
    {
        return $this->close_price;
    }

    public function getHighPrice(): string
    {
        return $this->high_price;
    }
}
