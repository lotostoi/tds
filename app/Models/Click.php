<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $fillable = [
        'click_id',
        'item_id',
        'marketplace',
        'link_id',
        'blogger_id',
        'utm_medium',
        'utm_source',
        'ip_address',
        'user_agent',
        'referer',
        'status',
        'pp_id',
        'seller_id',
        'url',
        'redirect_url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
