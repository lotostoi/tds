<?php

namespace App\Services\ImportExternalTraffic\Ozon;

use App\Services\ImportExternalTraffic\Dto\ImportExternalTrafficDto;
use App\Services\ImportExternalTraffic\ImportExternalTrafficStrategyInterface;

class OzonImportExternalTrafficSTrategy implements ImportExternalTrafficStrategyInterface
{
    public function import(ImportExternalTrafficDto $dto): int
    {
        return 0;
    }
}
