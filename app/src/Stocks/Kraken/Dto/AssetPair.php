<?php

declare(strict_types=1);

namespace App\src\Stocks\Kraken\Dto;

class AssetPair
{
    private string $altname;
    private string $wsname;
    private string $aclassBase;
    private string $base;
    private string $aclassQuote;
    private string $quote;

    public function __construct(
        string $altname,
        string $wsname,
        string $aclassBase,
        string $base,
        string $aclassQuote,
        string $quote
    ) {
        $this->altname = $altname;
        $this->wsname = $wsname;
        $this->aclassBase = $aclassBase;
        $this->base = $base;
        $this->aclassQuote = $aclassQuote;
        $this->quote = $quote;
    }

    public function getAltname(): string
    {
        return $this->altname;
    }

    public function getWsname(): string
    {
        return $this->wsname;
    }

    public function getAclassBase(): string
    {
        return $this->aclassBase;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getAclassQuote(): string
    {
        return $this->aclassQuote;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }
}
