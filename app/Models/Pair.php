<?php

declare(strict_types=1);

namespace App\Models;

use App\src\Stocks\Kraken\Dto\AssetPair;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    protected $table = 'pairs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'altname',
        'wsname',
        'aclass_base',
        'base',
        'aclass_quote',
        'quote',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public static function createFromAssetPairDto(AssetPair $assetPair): self
    {
        return self::create(
            [
                'altname' => $assetPair->getAltname(),
                'wsname' => $assetPair->getWsname(),
                'aclass_base' => $assetPair->getAclassBase(),
                'base' => $assetPair->getBase(),
                'aclass_quote' => $assetPair->getAclassBase(),
                'quote' => $assetPair->getQuote(),
                'is_active' => true,
                'created_at' => now()
            ]
        );
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getAltname(): string
    {
        return (string) $this->altname;
    }
}
