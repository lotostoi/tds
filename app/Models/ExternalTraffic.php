<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalTraffic extends Model
{
    use SoftDeletes;

    protected $table = 'external_traffic';

    protected $fillable = [
        'seller_id',
        'event_date',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'clicks',
        'ordered_items',
        'order_value_rub',
        'platform',
    ];

    protected $casts = [
        'event_date' => 'date',
        'clicks' => 'integer',
        'ordered_items' => 'integer',
        'order_value_rub' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function scopeBySeller(Builder $query, Seller $seller)
    {
        return $query->where('seller_id', $seller->id);
    }
}
