<?php

namespace App\Services\ImportExternalTraffic;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class AbstractImportController extends Controller implements ImportExternalTrafficStrategyInterface
{
    protected function parseExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
        $worksheet = $spreadsheet->getActiveSheet();

        $allRows = $worksheet->toArray();
        array_shift($allRows);

        return $allRows;
    }

}
