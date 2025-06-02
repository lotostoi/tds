<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCard extends Model
{
    protected $fillable = [
        'seller_id',
        'nm_id',
        'imt_id',
        'nm_uuid',
        'subject_id',
        'subject_name',
        'vendor_code',
        'brand',
        'title',
        'description',
        'need_kiz',
        'photos',
        'video',
        'dimensions',
        'characteristics',
        'sizes',
        'tags',
        'wb_created_at',
        'wb_updated_at',
    ];

    protected $casts = [
        'nm_id' => 'integer',
        'imt_id' => 'integer',
        'subject_id' => 'integer',
        'need_kiz' => 'boolean',
        'photos' => 'array',
        'dimensions' => 'array',
        'characteristics' => 'array',
        'sizes' => 'array',
        'tags' => 'array',
        'wb_created_at' => 'datetime',
        'wb_updated_at' => 'datetime',
    ];

    /**
     * Связь с продавцом
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Получить декриптированный API ключ продавца (для использования в API запросах)
     */
    public function getSellerApiKeyAttribute(): ?string
    {
        if ($this->seller && $this->seller->api_key) {
            return \Illuminate\Support\Facades\Crypt::decryptString($this->seller->api_key);
        }

        return null;
    }

    /**
     * Получить основное фото товара
     */
    public function getMainPhotoAttribute(): ?string
    {
        if ($this->photos && is_array($this->photos) && count($this->photos) > 0) {
            return $this->photos[0]['url'] ?? $this->photos[0] ?? null;
        }

        return null;
    }
}
