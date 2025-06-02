<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ProductCard;
use App\Models\Seller;
use App\Services\WildberriesApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportProductCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:import-cards {--seller-id= : ID конкретного продавца для импорта} {--limit=100 : Лимит карточек за один запрос}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт карточек товаров из API Wildberries с автоматической пагинацией';

    private WildberriesApiService $apiService;

    public function __construct(WildberriesApiService $apiService)
    {
        parent::__construct();
        $this->apiService = $apiService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Начинаем импорт карточек товаров из Wildberries...');

        $sellerId = $this->option('seller-id');
        $limit = (int) $this->option('limit');

        // Получаем продавцов для обработки
        if ($sellerId) {
            $sellers = Seller::where('id', $sellerId)->get();
            if ($sellers->isEmpty()) {
                $this->error("❌ Продавец с ID {$sellerId} не найден");
                return Command::FAILURE;
            }
        } else {
            $sellers = Seller::whereNotNull('api_key')->get();
        }

        if ($sellers->isEmpty()) {
            $this->error('❌ Не найдено продавцов с API ключами');
            return Command::FAILURE;
        }

        $this->info("📋 Найдено продавцов: {$sellers->count()}");

        $totalProcessed = 0;
        $totalUpdated = 0;
        $totalCreated = 0;
        $totalErrors = 0;

        foreach ($sellers as $seller) {
            $this->processSellerCards($seller, $limit, $totalProcessed, $totalUpdated, $totalCreated, $totalErrors);
        }

        $this->newLine();
        $this->info('✅ Импорт завершен!');
        $this->table(
            ['Метрика', 'Значение'],
            [
                ['Всего обработано', $totalProcessed],
                ['Создано новых', $totalCreated],
                ['Обновлено существующих', $totalUpdated],
                ['Ошибок', $totalErrors],
            ]
        );

        return Command::SUCCESS;
    }

    private function processSellerCards(
        Seller $seller,
        int $limit,
        int &$totalProcessed,
        int &$totalUpdated,
        int &$totalCreated,
        int &$totalErrors
    ): void {
        $this->info("🔄 Обрабатываем продавца: {$seller->title} (ID: {$seller->id})");

        $apiKey = $seller->getDecryptedApiKey();
        if (!$apiKey) {
            $this->warn("⚠️  У продавца {$seller->id} нет API ключа или не удалось его расшифровать");
            return;
        }

        $processedCount = 0;
        $createdCount = 0;
        $updatedCount = 0;
        $errorCount = 0;
        $pageCount = 0;

        $progressBar = $this->output->createProgressBar();
        $progressBar->setFormat(' %current% карточек | %message%');
        $progressBar->setMessage('Загружаем карточки...');
        $progressBar->start();

        try {
            foreach ($this->apiService->getAllProductCards($apiKey, $limit) as $cardData) {
                try {
                    // Отслеживаем страницы
                    if ($processedCount % $limit === 0) {
                        $pageCount++;
                        $progressBar->setMessage("Страница {$pageCount} | Создано: {$createdCount}, Обновлено: {$updatedCount}, Ошибок: {$errorCount}");
                    }

                    $transformedData = $this->apiService->transformCardData($cardData, $seller->id);

                    if (!$transformedData['nm_id']) {
                        $this->warn("⚠️  Пропускаем карточку без nm_id");
                        $errorCount++;
                        continue;
                    }

                    DB::beginTransaction();

                    $productCard = ProductCard::updateOrCreate(
                        [
                            'seller_id' => $seller->id,
                            'nm_id' => $transformedData['nm_id']
                        ],
                        $transformedData
                    );

                    if ($productCard->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $updatedCount++;
                    }

                    DB::commit();
                    $processedCount++;

                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorCount++;

                    Log::error('Ошибка при сохранении карточки товара', [
                        'seller_id' => $seller->id,
                        'card_data' => $cardData,
                        'error' => $e->getMessage()
                    ]);
                }

                $progressBar->advance();
                $progressBar->setMessage("Создано: {$createdCount}, Обновлено: {$updatedCount}, Ошибок: {$errorCount}");
            }

        } catch (\Exception $e) {
            $this->error("❌ Ошибка при получении карточек для продавца {$seller->id}: {$e->getMessage()}");
            Log::error('Ошибка импорта карточек', [
                'seller_id' => $seller->id,
                'error' => $e->getMessage()
            ]);
        }

        $progressBar->finish();

        $this->newLine();

        $this->info("📊 Продавец {$seller->id}: обработано {$processedCount}, создано {$createdCount}, обновлено {$updatedCount}, ошибок {$errorCount}, страниц {$pageCount}");

        $totalProcessed += $processedCount;
        $totalCreated += $createdCount;
        $totalUpdated += $updatedCount;
        $totalErrors += $errorCount;
    }
}
