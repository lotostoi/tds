<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WildberriesApiService
{
    private const API_BASE_URL = 'https://content-api.wildberries.ru';
    private const CARDS_LIST_ENDPOINT = '/content/v2/get/cards/list';

    /**
     * Получить все карточки товаров с пагинацией
     */
    public function getProductCards(string $apiKey, int $limit = 100, ?array $cursor = null): array
    {
        $requestData = [
            'settings' => [
                'cursor' => [
                    'limit' => $limit
                ],
                'filter' => [
                    'withPhoto' => -1
                ]
            ]
        ];

        // Добавляем cursor для пагинации если есть
        if ($cursor) {
            if (isset($cursor['updatedAt'])) {
                $requestData['settings']['cursor']['updatedAt'] = $cursor['updatedAt'];
            }
            if (isset($cursor['nmID'])) {
                $requestData['settings']['cursor']['nmID'] = $cursor['nmID'];
            }
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post(self::API_BASE_URL . self::CARDS_LIST_ENDPOINT, $requestData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Wildberries API error', [
                'status' => $response->status(),
                'response' => $response->body(),
                'request_data' => $requestData
            ]);

            return [];

        } catch (\Exception $e) {
            Log::error('Wildberries API exception', [
                'message' => $e->getMessage(),
                'request_data' => $requestData
            ]);

            return [];
        }
    }

    /**
     * Получить все карточки товаров с автоматической пагинацией
     */
    public function getAllProductCards(string $apiKey, int $limit = 100): \Generator
    {
        $cursor = null;
        $hasMoreData = true;

        while ($hasMoreData) {
            $response = $this->getProductCards($apiKey, $limit, $cursor);

            if (empty($response) || !isset($response['cards'])) {
                break;
            }

            $cards = $response['cards'];
            $cardsCount = count($cards);

            // Отдаем карточки
            foreach ($cards as $card) {
                yield $card;
            }

            // Проверяем нужна ли дальнейшая пагинация
            // Если получили меньше карточек чем лимит, значит это последняя страница
            if ($cardsCount < $limit) {
                $hasMoreData = false;
            } else {
                // Получаем cursor для следующего запроса из последней карточки
                if (!empty($cards)) {
                    $lastCard = end($cards);
                    $cursor = [
                        'updatedAt' => $lastCard['updatedAt'] ?? null,
                        'nmID' => $lastCard['nmID'] ?? null
                    ];

                    // Если нет данных для cursor, прекращаем
                    if (!$cursor['updatedAt'] || !$cursor['nmID']) {
                        $hasMoreData = false;
                    }
                } else {
                    $hasMoreData = false;
                }
            }

            // Добавляем небольшую задержку между запросами
            usleep(100000); // 0.1 секунды
        }
    }

    /**
     * Преобразовать данные карточки из API в формат для базы данных
     */
    public function transformCardData(array $cardData, int $sellerId): array
    {
        return [
            'seller_id' => $sellerId,
            'nm_id' => $cardData['nmID'] ?? null,
            'imt_id' => $cardData['imtID'] ?? null,
            'nm_uuid' => $cardData['nmUUID'] ?? null,
            'subject_id' => $cardData['subjectID'] ?? null,
            'subject_name' => $cardData['subjectName'] ?? null,
            'vendor_code' => $cardData['vendorCode'] ?? null,
            'brand' => $cardData['brand'] ?? null,
            'title' => $cardData['title'] ?? null,
            'description' => $cardData['description'] ?? null,
            'need_kiz' => $cardData['needKiz'] ?? false,
            'photos' => $cardData['photos'] ?? null,
            'video' => $cardData['video'] ?? null,
            'dimensions' => $cardData['dimensions'] ?? null,
            'characteristics' => $cardData['characteristics'] ?? null,
            'sizes' => $cardData['sizes'] ?? null,
            'tags' => $cardData['tags'] ?? null,
            'wb_created_at' => isset($cardData['createdAt']) ? \Carbon\Carbon::parse($cardData['createdAt']) : null,
            'wb_updated_at' => isset($cardData['updatedAt']) ? \Carbon\Carbon::parse($cardData['updatedAt']) : null,
        ];
    }
}
