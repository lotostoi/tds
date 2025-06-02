<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_cards', function (Blueprint $table) {
            $table->id();

            // Связь с продавцом
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');

            // Основные идентификаторы
            $table->bigInteger('nm_id')->comment('Артикул WB');
            $table->bigInteger('imt_id')->comment('ID объединённой карточки товара');
            $table->uuid('nm_uuid')->comment('Внутренний технический ID карточки товара');

            // Предмет
            $table->integer('subject_id')->comment('ID предмета');
            $table->string('subject_name')->comment('Название предмета');

            // Основная информация о товаре
            $table->string('vendor_code')->nullable()->comment('Артикул продавца');
            $table->string('brand')->nullable()->comment('Бренд');
            $table->string('title')->comment('Наименование товара');
            $table->text('description')->nullable()->comment('Описание товара');

            // Маркировка
            $table->boolean('need_kiz')->default(false)->comment('Требуется ли код маркировки');

            // Медиа контент (JSON массивы/объекты)
            $table->json('photos')->nullable()->comment('Массив фото');
            $table->string('video')->nullable()->comment('URL видео');

            // Габариты и характеристики (JSON объекты)
            $table->json('dimensions')->nullable()->comment('Габариты и вес товара c упаковкой');
            $table->json('characteristics')->nullable()->comment('Характеристики');
            $table->json('sizes')->nullable()->comment('Размеры товара');
            $table->json('tags')->nullable()->comment('Ярлыки');

            // Даты создания и обновления (кастомные, не Laravel timestamps)
            $table->timestamp('wb_created_at')->nullable()->comment('Дата создания в WB');
            $table->timestamp('wb_updated_at')->nullable()->comment('Дата обновления в WB');

            // Laravel timestamps
            $table->timestamps();

            // Индексы
            $table->index('nm_id');
            $table->index('imt_id');
            $table->index('subject_id');
            $table->index(['seller_id', 'nm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_cards');
    }
};
