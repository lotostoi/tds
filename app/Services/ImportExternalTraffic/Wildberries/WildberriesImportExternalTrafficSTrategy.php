<?php

namespace App\Services\ImportExternalTraffic\Wildberries;

use App\Models\ExternalTraffic;
use App\Services\ImportExternalTraffic\AbstractImportController;
use App\Services\ImportExternalTraffic\Dto\ImportExternalTrafficDto;

class WildberriesImportExternalTrafficSTrategy extends AbstractImportController
{
    public function import(ImportExternalTrafficDto $dto): int
    {
        try {
            
            $rows = $this->parseExcelFile($dto->getFilePath());

            $importedCount = 0;

            foreach ($rows as $row) {

                if (empty(array_filter($row))) continue;

                $data = $this->parseRow($row, $dto->getSellerId());

                if ($this->isHasRequiredFields($row)) {

                    $alreadyExists = ExternalTraffic::where($data)
                        ->where('seller_id', $dto->getSellerId())
                        ->where('event_date', $data['event_date'])
                        ->where('utm_term', $data['utm_term'])
                        ->first();

                    if ($alreadyExists) {
                        $alreadyExists->update($data);
                    } else {
                        ExternalTraffic::create($data);
                    }

                    $importedCount++;
                }
            }

            // Удаляем временный файл
            unlink(storage_path('app/public/' . $dto->getFilePath()));

            return $importedCount;

        } catch (\Exception $e) {

            if (file_exists(storage_path('app/public/' . $dto->getFilePath()))) {
                unlink(storage_path('app/public/' . $dto->getFilePath()));
            }

            throw $e;
        }
    }

    private function parseRow(array $row, int $sellerId): array
    {
        return               [
            'seller_id' => $sellerId,
            'event_date' => $this->parseDate($row[0] ?? null),
            'utm_source' => $row[1] ?? '',
            'utm_medium' => $row[2] ?? '',
            'utm_campaign' => $row[3] ?? null,
            'utm_term' => $row[4] ?? '',
            'utm_content' => $row[5] ?? null,
            'clicks' => (int)($row[6] ?? 0),
            'ordered_items' => (int)($row[7] ?? 0),
            'order_value_rub' => (float)($row[8] ?? 0),
            'platform' => $row[9] ?? null,
        ];
    }

    private function parseDate(string $dateValue): ?string
    {
        if (empty($dateValue)) {
            return null;
        }

        try {
            return date('Y-m-d', strtotime($dateValue));
        } catch (\Exception $e) {
            return null;
        }
    }

    private function isHasRequiredFields(array $row): bool
    {
        return !empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3]);
    }
}
