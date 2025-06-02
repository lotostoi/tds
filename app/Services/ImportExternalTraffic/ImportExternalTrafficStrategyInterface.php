<?php

namespace App\Services\ImportExternalTraffic;

use App\Services\ImportExternalTraffic\Dto\ImportExternalTrafficDto;

interface ImportExternalTrafficStrategyInterface
{
    public function import(ImportExternalTrafficDto $dto): int;
}
