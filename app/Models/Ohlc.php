<?php

declare(strict_types=1);

namespace App\Models;

use App\src\Stocks\Kraken\Dto\OhlcPerMoment;
use Illuminate\Database\Eloquent\Model;

class Ohlc extends Model
{
    protected $table = 'ohlc';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
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
}
