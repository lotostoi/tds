<?php

namespace App\Services\ImportExternalTraffic;

use App\Services\ImportExternalTraffic\Dto\ImportExternalTrafficDto;
use App\Services\ImportExternalTraffic\Ozon\OzonImportExternalTrafficSTrategy;
use App\Services\ImportExternalTraffic\Wildberries\WildberriesImportExternalTrafficSTrategy;
use App\Enums\SellerPlatforms;

class StrategyImportExternalTrafficFactory
{
    public function make(ImportExternalTrafficDto $dto): ImportExternalTrafficStrategyInterface
    {
        return match ($dto->getPlatform()) {
            SellerPlatforms::OZON->value => new OzonImportExternalTrafficSTrategy(),
            SellerPlatforms::WILDBERRIES->value => new WildberriesImportExternalTrafficSTrategy(),
            
            default => throw new \Throwable('Invalid platform'),
        };
    }
}
