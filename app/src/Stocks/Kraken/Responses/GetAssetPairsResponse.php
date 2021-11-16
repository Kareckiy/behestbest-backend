<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Responses;

use App\src\Stocks\Kraken\Dto\AssetPair;

class GetAssetPairsResponse extends BaseResponse
{
    /** @var AssetPair[] */
    private array $assetPairs;

    public function __construct(array $assetPairs)
    {
        $this->assetPairs = $assetPairs;
    }

    public static function createFromJson(string $json): self
    {
        $assetPairs = [];
        try {
            $jsonResponse = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $assetPairs = $jsonResponse['result'];
        } catch (\Exception $e) {
            // logger()->warning();
        }

        $pairs = [];
        foreach ($assetPairs as $assetPair) {
            $pairs[] = new AssetPair(
                (string) $assetPair['altname'],
                (string) $assetPair['wsname'],
                (string) $assetPair['aclass_base'],
                (string) $assetPair['base'],
                (string) $assetPair['aclass_quote'],
                (string) $assetPair['quote']
            );
        }

        return new self($pairs);
    }

    public function getAssetPairs(): array
    {
        return $this->assetPairs;
    }
}
